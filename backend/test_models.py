import google.generativeai as genai

# Configure with your API key
genai.configure(api_key="AIzaSyDJ6YxA4nSg_IoMmyISyjvuWIfig6nHBAw")  # Replace this!

# List available models
models = genai.list_models()
for model in models:
    print(model.name)