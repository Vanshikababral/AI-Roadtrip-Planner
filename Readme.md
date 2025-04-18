
# 🚗 AI Road Trip Planner

**AI Road Trip Planner** is a web application that helps users plan scenic road trips with the help of an AI-powered chatbot. It generates routes, suggests stops, recommends accommodations, and provides weather and road condition updates.

---

## 🧰 Tech Stack

### 🔤 Languages Used:
- **Frontend**: HTML, CSS, JavaScript, PHP
- **Backend**:
  - PHP (for user management and database interaction)
  - Python (Flask for AI chatbot using Gemini API)
- **Database**: MySQL (via XAMPP)

---

## 📦 Libraries & Tools Used

### 📚 Python (Backend AI Chatbot)
- `Flask` – to build the backend API.
- `google.generativeai` – Gemini API for AI-driven route and conversation generation.
- `Flask-CORS` – to enable Cross-Origin Resource Sharing for frontend-backend communication.

Install Python dependencies:
```bash
pip install flask flask-cors google-generativeai
```

### 💻 PHP & Web (Frontend)
- Plain HTML/CSS for UI
- JavaScript (for dynamic chatbot UI and trip planner form)
- PHP for backend logic and templating
- AJAX (`fetch`) for communicating with the Flask backend

---

## 🗃 Folder Structure

```
ai-roadtrip-planner/
├── backend/
│   ├── app.py               # Flask server using Gemini AI
│   └── __pycache__/
├── database/
│   ├── db_config.php        # DB connection config
│   ├── setup.sql            # SQL setup script
│   ├── signup.php           # Signup logic
│   ├── validate_login.php   # Login verification
│   ├── contact.php          # Contact form handling
├── frontend/
│   ├── index.php            # Homepage (includes chatbot + trip planner)
│   ├── login.php
│   ├── signup.php
│   ├── contact.php
│   ├── header.php
│   ├── footer.php
│   └── css/
│       ├── index.css
│       └── chatbot.css
├── .env (optional)          # For storing Gemini API key
└── README.md
```

---

## 🛠️ Project Setup & Installation

### 📌 Prerequisites
- [XAMPP](https://www.apachefriends.org/index.html) (Apache + MySQL)
- Python 3.10+
- Composer (optional, if you add PHP libraries)

---

### 🔌 Backend Setup (AI Chatbot)

1. **Navigate to the backend folder**:
   ```bash
   cd ai-roadtrip-planner/backend
   ```

2. **Install Python packages**:
   ```bash
   pip install flask flask-cors google-generativeai
   ```

3. **Add your Gemini API Key** inside `app.py`:
   ```python
   genai.configure(api_key="YOUR_API_KEY")
   ```

4. **Run the Flask backend**:
   ```bash
   python app.py
   ```
   - This starts the Flask server on `http://localhost:5000`

---

### 🧩 Frontend + PHP Backend Setup (XAMPP)

1. **Place your project folder** inside:
   ```
   C:\xampp\htdocs\ai-roadtrip-planner
   ```

2. **Start Apache and MySQL** using XAMPP Control Panel.

3. **Import database**:
   - Go to `http://localhost/phpmyadmin`
   - Create a database named `roadtrip_planner`
   - Import `database/setup.sql` file

4. **Visit the app in browser**:
   ```
   http://localhost/ai-roadtrip-planner/frontend/index.php
   ```

---

## 🔄 Frontend-Backend Integration

- The **PHP frontend** handles user login/signup and includes the main chatbot interface (`index.php`).
- When a user sends a message, JavaScript sends it via `fetch()` to the Python **Flask backend**:
  ```javascript
  fetch('http://localhost:5000/chat', {
      method: 'POST',
      body: JSON.stringify({ message }),
      ...
  });
  ```
- The Flask app processes it using Gemini API and returns the response.

---

## 🧠 Features

✅ User Authentication  
✅ AI-Powered Chatbot for Route Planning  
✅ Scenic Route Suggestions  
✅ Stop Recommendations  
✅ Contact Form with Database Storage  
✅ Responsive UI  
✅ Session-based login state

---

## 🚨 Common Errors & Fixes

### ❌ 404 Not Found (on `/chat`)
- Ensure the Flask backend is **running** at `localhost:5000`.
- Check if CORS is properly set in `app.py`.

### ❌ `ModuleNotFoundError: No module named 'google'`
- Run `pip install google-generativeai` in your virtual environment.

### ❌ Footer not aligned
- Check `index.css` for `min-height` on main container and `position: relative` on wrapper.

---

## 🙌 Contributions
Want to contribute? Fork this project and submit a PR!

---

## 📧 Contact

For issues or suggestions, contact us via the **Contact Page** in the app or email: `roadtripplanner@demo.com`

DETAILED DESCRIPTION OF BACKEND

# 🧠 Backend Architecture – AI Road Trip Planner

---

## 📌 Overview

The backend is a hybrid system made up of two parts:

1. **PHP Backend (Traditional server-side backend)**  
   - Handles user authentication, contact form submissions, and communication with the MySQL database.

2. **Python Backend (Flask API)**  
   - Powers the AI chatbot using the **Gemini API** (by Google).
   - Communicates with the frontend asynchronously using **fetch() API** (AJAX).

---

## 🔧 1. PHP Backend

### 🗂 Location:
`/ai-roadtrip-planner/database/`

### 💼 Responsibilities:
- User registration and login.
- Validating user credentials.
- Handling contact form and inserting messages into the database.
- Connecting securely to MySQL using PDO.

---

### 🧩 PHP Files & What They Do

#### 🔐 `signup.php`
- Inserts new user records into the `users` table.
- Validates email uniqueness and password format.

#### 🔓 `validate_login.php`
- Validates user credentials against the database.
- Initiates PHP session if login is successful.

#### 📥 `contact.php`
- Accepts contact form input (name, email, message).
- Stores the data in `contact_messages` table.

#### ⚙️ `db_config.php`
- Stores MySQL connection config using PDO.
- Central file included by all others for database access.

#### 🗃️ Database Tables (in `setup.sql`)
- `users (id, name, email, password)`
- `contact_messages (id, name, email, message, created_at)`

---

## 🔧 2. Python Backend (Flask + Gemini AI)

### 🗂 Location:
`/ai-roadtrip-planner/backend/app.py`

### 🧠 Purpose:
- Provide intelligent chatbot responses for road trip planning.
- Communicate with Gemini API using the `google.generativeai` SDK.

---

### 🔌 How It Works

#### ✅ Flask App Setup
```python
from flask import Flask, request, jsonify
from flask_cors import CORS
import google.generativeai as genai
```

- The app uses `CORS` to allow communication from `localhost` (where PHP frontend runs).
- The Gemini API key is configured like this:
```python
genai.configure(api_key="YOUR_API_KEY")
model = genai.GenerativeModel("gemini-pro")
```

#### 🌐 Routes

##### `/chat` – POST
- **Input**: `{ "message": "Plan trip from A to B" }`
- **Output**: `{ "response": "Here's your scenic route from A to B..." }`

```python
@app.route('/chat', methods=['POST'])
def chat():
    user_input = request.json.get("message")
    response = model.generate_content(user_input)
    return jsonify({"response": response.text})
```

#### 🌐 `/` – GET
- Health check route: returns "AI Road Trip Planner is running."

---

## 🔁 Frontend–Backend Integration

### ✅ From PHP Frontend
- The `index.php` file includes the chatbot UI and sends user messages via `fetch()`:
```javascript
fetch('http://localhost:5000/chat', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ message: userInput })
});
```

- The Flask backend receives the input, uses Gemini to generate a response, and returns it to the JavaScript, which displays it in the chat window.

---

## 🔐 Security Notes

- CORS is enabled but restricted to localhost for development:
  ```python
  CORS(app, origins=["http://localhost"])
  ```
- Passwords are expected to be hashed and validated (can use `password_hash()` in PHP).
- Database access uses PDO with prepared statements to prevent SQL injection.

---

## 💬 Example Interaction Flow

1. **User enters trip request**:  
   `Plan a trip from Pathankot to Jalandhar`

2. **Frontend sends message to `/chat`** (Flask):
   ```json
   { "message": "Plan a trip from Pathankot to Jalandhar" }
   ```

3. **Flask handles the request**:
   - Sends it to Gemini API.
   - Gets AI-generated content.

4. **Response is sent back to frontend**:
   ```json
   { "response": "Here's a scenic route from Pathankot to Jalandhar with interesting stops..." }
   ```

5. **Frontend displays it** in the chatbot UI.

---

## ⚙️ Development Tips

- Always run the Flask backend (`python app.py`) **before** using the chatbot UI.
- Keep both `XAMPP` and Flask running in parallel.
- Use `console.log()` or browser console to debug fetch errors.
- Make sure Flask is running on `http://localhost:5000` and not being blocked by a firewall or antivirus.

---