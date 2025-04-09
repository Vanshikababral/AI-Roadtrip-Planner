from flask import Flask, request, jsonify
from flask_cors import CORS
import google.generativeai as genai
import os
from dotenv import load_dotenv
import logging
import re

# Load environment variables
load_dotenv()
api_key = os.getenv("GEMINI_API_KEY")

if not api_key:
    raise ValueError("No GEMINI_API_KEY found in environment variables. Please set it in .env file.")

# Configure Gemini API
genai.configure(api_key=api_key)

# Initialize Flask app
app = Flask(__name__)
CORS(app)

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s [%(levelname)s] %(message)s",
    handlers=[logging.StreamHandler()]
)

# In-memory context store
conversation_context = {}

# Keywords to detect travel-related intent
TRAVEL_KEYWORDS = [
    "trip", "route", "plan", "travel", "destination", "stop", "hotel", "food",
    "accommodation", "cuisine", "weather", "road", "drive", "bus", "taxi", "flight", "train"
]

def is_travel_related(message):
    """Check if the message is related to travel planning."""
    message_lower = message.lower()
    return any(keyword in message_lower for keyword in TRAVEL_KEYWORDS)

@app.route('/chat', methods=['POST'])
def chat():
    try:
        data = request.get_json()
        if not data or "message" not in data:
            return jsonify({"error": "Invalid request: 'message' field required"}), 400

        user_message = data.get("message", "").strip()
        session_id = data.get("session_id", "default")
        if not user_message:
            return jsonify({"error": "Message is empty"}), 400

        # Initialize Gemini model
        gemini_model = genai.GenerativeModel(
            model_name="gemini-1.5-pro",
            generation_config={"temperature": 0.7, "max_output_tokens": 1000}
        )

        # Initialize context for this session if not exists
        if session_id not in conversation_context:
            conversation_context[session_id] = {"original_trip": None}

        # Check if this is a travel-related request
        if is_travel_related(user_message):
            # Update context if it's a new trip request
            if any(keyword in user_message.lower() for keyword in ["plan my trip", "from", "to"]):
                conversation_context[session_id]["original_trip"] = user_message

            # Use context only if it exists and the message is a follow-up
            context = conversation_context[session_id]["original_trip"]
            if context and context != user_message:
                roadtrip_prompt = f"""
                You are a road trip planning assistant. The user previously asked about a trip: "{context}". 
                Now, based on their latest input: "{user_message}", provide a relevant response in the context of that trip.

                - If the latest input is a follow-up question (e.g., about routes, stops, transport, cuisine, hotels, or weather), answer it specifically for the original trip.
                - Return the response as raw HTML content (do not prepend "html" or add extra labels) using:
                    <h2> for the title (e.g., "Travel Options for [Trip]"),
                    <div class="bg-light p-3 my-2"> for each section or answer,
                    <strong> to highlight key details.
                - Keep the response concise, under 300 words.
                """
            else:
                roadtrip_prompt = f"""
                You are a road trip planning assistant. Based on the user's input: "{user_message}", generate a scenic and interesting road trip plan or answer their travel-related question.

                - If planning a trip, suggest a route from the start to end location, scenic stops, attractions, or towns, one good place to eat, and one nice hotel.
                - If answering a question (e.g., about cuisine, weather, or transport), provide specific details.
                - Return the response as raw HTML content (do not prepend "html" or add extra labels) using:
                    <h2> for the trip title or question topic,
                    <div class="bg-light p-3 my-2"> for each section (route, stops, hotels, food, etc.),
                    <strong> to highlight names or places.
                - Keep the response concise, under 300 words.
                """
        else:
            # Non-travel-related question, no context needed
            roadtrip_prompt = f"""
            You are a helpful assistant. Based on the user's input: "{user_message}", provide a concise and relevant response.

            - Answer the question directly without assuming a travel context unless explicitly mentioned.
            - Return the response as raw HTML content (do not prepend "html" or add extra labels) using:
                <h2> for the main answer or topic,
                <div class="bg-light p-3 my-2"> for the response body,
                <strong> to highlight key details if applicable.
            - Keep the response under 300 words.
            """

        # Generate response
        response = gemini_model.generate_content(roadtrip_prompt)
        response_text = getattr(response, "text", "").strip()

        # Clean up any unexpected "html" prefix
        if response_text.lower().startswith("html"):
            response_text = response_text[4:].strip()

        logging.info("Session %s - Gemini response: %s", session_id, response_text)
        print("Gemini raw response object:", response)

        if response_text:
            return jsonify({"reply": response_text})
        else:
            logging.warning("Gemini returned no valid text for session %s", session_id)
            return jsonify({"error": "No valid text response from Gemini"}), 500

    except genai.GenerationError as gen_error:
        logging.error("Gemini generation error: %s", str(gen_error))
        return jsonify({"error": "Failed to generate response", "details": str(gen_error)}), 500
    except Exception as e:
        logging.exception("Unexpected error in /chat endpoint")
        return jsonify({"error": "An error occurred while processing your message", "details": str(e)}), 500

if __name__ == "__main__":
    try:
        app.run(host='0.0.0.0', port=5000, debug=True)
    except Exception as e:
        logging.error("Failed to start Flask server: %s", str(e))
        raise