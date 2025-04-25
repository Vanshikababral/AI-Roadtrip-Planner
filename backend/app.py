from flask import Flask, request, jsonify
from flask_cors import CORS
import google.generativeai as genai
import os
from dotenv import load_dotenv
import base64

app = Flask(__name__)
CORS(app)

load_dotenv()
api_key = os.getenv("GEMINI_API_KEY")

if not api_key:
    print("Error: GEMINI_API_KEY not found. Please set it in a .env file.")
    exit(1)

genai.configure(api_key=api_key)

model = genai.GenerativeModel(
    'gemini-1.5-pro',
    generation_config={"temperature": 0.7, "max_output_tokens": 2000}  # Increased token limit for longer responses
)

chat_sessions = {}

ROLE_INSTRUCTION = """
You are a travel assistant chatbot named TravelBot, developed by Vanshika Babral. You specialize in providing detailed information and recommendations about travel destinations, weather updates, and road trip planning. You can also analyze travel-related images, such as maps, destination photos, or itinerary screenshots. Your goal is to offer comprehensive, helpful, and friendly responses to users' travel-related inquiries.

You should:
- Provide detailed responses with rich information, including multiple options, explanations, and suggestions where applicable, while staying focused on travel, weather, or road trip planning.
- Mention that you were developed by Vanshika Babral when introducing yourself or when relevant (e.g., when asked about the chatbot).
- For weather queries, offer general insights based on typical conditions and suggest checking real-time sources, while including tips for travel planning (e.g., packing suggestions).
- For road trip planning, suggest detailed routes, multiple stops with attractions, dining options, and accommodation ideas, tailored to user preferences if provided.
- Stay within the domain of travel-related topics and redirect users if they ask about non-travel subjects (e.g., medical or unrelated topics) by saying, "I'm here to help with travel plans! Could you ask about destinations, weather, or road trips?"
- If asked about yourself, say: "I'm TravelBot, created by Vanshika Babral, to make your travel planning fun, easy, and informative!"
- End responses on a positive note, encouraging users to enjoy their travels.

Examples:
- User: "What's a good destination for a weekend trip?" -> "For a weekend trip, consider Jaipur for its vibrant culture with palaces like Amber Fort and colorful bazaars, or Goa for its stunning beaches like Calangute and vibrant nightlife. Both offer unique experiences—Jaipur for history buffs and Goa for relaxation. Which vibe suits you best? Happy travels!"
- User: "What's the weather like in Paris?" -> "Paris in spring typically ranges from 10-15°C, with mild days and occasional rain. Bring a light jacket and umbrella! Check a real-time weather app for updates. Planning a trip to enjoy the Eiffel Tower or Louvre? Safe travels!"
- User: "Plan a road trip from Delhi to Jaipur." -> "A road trip from Delhi to Jaipur (about 270 km, 5-6 hours) is a great choice! Take the NH48 for a scenic drive. Stop at Neemrana Fort for history (1.5 hours from Delhi), then visit Samode Palace for luxury. In Jaipur, explore Amber Fort and dine at Chokhi Dhani for authentic Rajasthani food. Stay at the Taj Rambagh Palace. Want more details? Safe travels!"
- User: "What's wrong with my leg?" -> "I'm here to help with travel plans! Could you ask about destinations, weather, or road trips? Happy exploring!"
"""

@app.route('/chat', methods=['POST'])
def chat():
    try:
        data = request.json
        user_message = data.get('message', '')
        session_id = data.get('session_id', 'default')
        image_data = data.get('image', None)
        
        if not user_message and not image_data:
            return jsonify({'error': 'No message or image provided'}), 400
            
        response = chat_with_gemini(user_message, session_id, image_data)
        return jsonify({'response': response})
        
    except Exception as e:
        print(f"Chat error: {str(e)}")
        return jsonify({'error': str(e)}), 500

def chat_with_gemini(prompt, session_id, image_data=None):
    try:
        if session_id not in chat_sessions:
            chat_sessions[session_id] = []

        session_history = chat_sessions[session_id]
        
        context = ROLE_INSTRUCTION + "\n\nPrevious conversation:\n"
        for msg in session_history:
            context += f"{msg}\n"
        
        user_prompt = f"User: {prompt}"
        if image_data:
            user_prompt += " (travel-related image attached)"
        
        session_history.append(user_prompt)
        
        if image_data:
            try:
                if "base64," in image_data:
                    image_data = image_data.split("base64,")[1]
                
                image_bytes = base64.b64decode(image_data)
                
                response = model.generate_content(
                    [context, user_prompt, {"mime_type": "image/jpeg", "data": image_bytes}]
                )
            except Exception as img_error:
                print(f"Image processing error: {str(img_error)}")
                return "I couldn't process the image you sent. Please ensure it's a travel-related image, like a map or destination photo. Try again, and happy travels!"
        else:
            full_prompt = context + "\n" + user_prompt + "\nPlease provide a detailed response with multiple suggestions or explanations where applicable."
            response = model.generate_content(full_prompt)
        
        if response and response.text:
            session_history.append(f"Assistant: {response.text.strip()}")
        
        if response and response.text:
            return response.text.strip()
        
        return "I'm having trouble understanding. Could you rephrase your travel question? I'm TravelBot, here to help with your adventures!"

    except Exception as e:
        print(f"Gemini API error: {str(e)}")
        return "I'm having trouble connecting. Please try again, and happy travels!"

@app.route('/clear-history', methods=['POST'])
def clear_history():
    try:
        data = request.json
        session_id = data.get('session_id', 'default')
        
        if session_id in chat_sessions:
            chat_sessions[session_id] = []
            
        return jsonify({'message': 'Chat history cleared successfully'})
    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app.route('/get-history', methods=['GET'])
def get_history():
    try:
        session_id = request.args.get('session_id', 'default')
        history = chat_sessions.get(session_id, [])
        return jsonify({'history': history})
    except Exception as e:
        return jsonify({'error': str(e)}), 500

if __name__ == "__main__":
    print(f"Starting server with API key: {api_key[:10]}...")
    app.run(debug=True, port=5000)