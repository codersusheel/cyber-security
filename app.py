from flask import Flask, render_template_string, jsonify

app = Flask(__name__)

# HTML टेंप्लेट
HTML_TEMPLATE = """
<!DOCTYPE html>
<html>
<head>
    <title>ProVen Committee App</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; background-color: #f4f4f9; }
        .card { background: white; padding: 20px; border-radius: 8px; display: inline-block; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        p { color: #666; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Welcome to ProVen Committee</h1>
        <p>BBA Team Management & Outreach System</p>
    </div>
</body>
</html>
"""

# होम रूट (Home Route)
@app.route('/')
def home():
    return render_template_string(HTML_TEMPLATE)

# एपीआई रूट (API Route)
@app.route('/api/status')
def status():
    return jsonify({
        "status": "Active",
        "project": "Pending Outreach",
        "assigned_to": "BBA Student Lead"
    })

if __name__ == '__main__':
    app.run(debug=True)
