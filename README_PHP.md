# 🏘️ SmartHabit-MY - PHP Version

**AI-Driven Housing Affordability Dashboard for XAMPP**

This is the PHP version of SmartHabit-MY, converted from the original Python/Streamlit application to run on XAMPP.

---

## 🚀 Quick Start with XAMPP

### Prerequisites
- XAMPP installed (Apache + PHP 7.4 or higher)
- Web browser

### Installation Steps

1. **Copy to XAMPP htdocs folder:**
   ```bash
   # Copy the firstprototype folder to your XAMPP htdocs directory
   # Windows: C:\xampp\htdocs\smarthabit
   # Mac: /Applications/XAMPP/htdocs/smarthabit
   # Linux: /opt/lampp/htdocs/smarthabit
   ```

2. **Start XAMPP:**
   - Open XAMPP Control Panel
   - Start Apache server

3. **Access the application:**
   - Open your browser
   - Navigate to: `http://localhost/smarthabit/` or `http://localhost/firstprototype/`

That's it! The dashboard should now be running.

---

## 📁 Project Structure

```
firstprototype/
├── index.php              # Main application file
├── .htaccess             # Apache configuration
├── assets/
│   ├── style.css         # Modern dark theme styling
│   └── script.js         # Interactive JavaScript
├── includes/
│   ├── data.php          # Housing data generation
│   ├── fuzzy_logic.php   # Fuzzy inference system
│   └── ml_predictor.php  # Price prediction model
└── README_PHP.md         # This file
```

---

## 💡 Key Features

### 1. **Interactive Geospatial Map (Leaflet.js + OpenStreetMap)**
- Real-time housing location visualization
- Color-coded markers (all options, filtered, best match)
- Interactive popups with property details
- Centered on Kuala Lumpur & Selangor
- Legend and custom styling

### 2. **Fuzzy Logic Livability Engine**
- Pure PHP implementation of fuzzy inference
- Triangular membership functions
- 9 fuzzy rules for livability assessment

### 3. **ML Price Predictor**
- Trained using Random Forest & Neural Networks
- Predicts fair rental prices
- Based on distance, amenities, safety, age, and size

### 4. **Interactive Dashboard**
- Real-time form updates
- Interactive Plotly charts
- Responsive design
- Dark theme with neon accents

### 5. **AI Model Training (Google Colab)**
- TensorFlow/Keras neural network
- scikit-learn Random Forest
- HuggingFace NLP sentiment analysis
- Complete training notebook included

---

## 🎨 Technology Stack

### Backend:
- **PHP 7.4+** - Server-side logic
- **Apache** - Web server (XAMPP)

### Frontend:
- **HTML5, CSS3, JavaScript** - Core web technologies
- **Leaflet.js** - Interactive mapping
- **OpenStreetMap** - Map tiles and geospatial data
- **Plotly.js** - Data visualization

### AI/ML (Training):
- **TensorFlow/Keras** - Deep learning
- **scikit-learn** - Traditional ML (Random Forest)
- **HuggingFace Transformers** - NLP models
- **Google Colab** - Cloud training environment

### Data Sources:
- **Malaysia Open Data Portal** - Government datasets
- **OpenStreetMap** - Geospatial data
- **Kaggle** - Housing datasets
- **DOSM** - Statistics Malaysia

---

## 🔧 Configuration

### Apache Configuration
The `.htaccess` file handles:
- URL rewriting
- Security headers
- Session management
- Error reporting (disable in production)

### PHP Settings
Minimum requirements:
- PHP 7.4 or higher
- Session support enabled
- JSON extension enabled

---

## 📊 How It Works

1. **User Input**: Select preferences via sidebar form
2. **Data Filtering**: PHP filters 200 synthetic housing records
3. **AI Analysis**: 
   - Fuzzy logic calculates livability scores
   - ML model predicts fair prices
4. **Visualization**: Plotly.js renders interactive charts
5. **Recommendations**: AI-powered suggestions displayed

---

## 🎯 Usage Example

1. Select location (e.g., "Cyberjaya")
2. Set budget slider to RM 2000
3. Choose max distance to MRT: 5 km
4. Set minimum amenities: 8
5. Click "Find Housing"
6. View AI recommendations and interactive charts

---

## 🔮 Future Enhancements

- Database integration (MySQL)
- User authentication
- Save favorite properties
- Email notifications
- Real property API integration
- Admin panel for data management

---

## 🐛 Troubleshooting

### Apache won't start
- Check if port 80 is already in use
- Try changing Apache port in XAMPP config

### Page shows blank
- Check PHP error logs in XAMPP
- Ensure all files are in correct directories
- Verify PHP version is 7.4+

### Charts not displaying
- Check browser console for JavaScript errors
- Ensure internet connection (Plotly CDN)
- Clear browser cache

---

## 📝 License

MIT License - Free to use and modify

---

## 👨‍💻 Developer Notes

This PHP version maintains the core functionality of the original Python app while being optimized for XAMPP deployment. The fuzzy logic and ML algorithms have been simplified but remain functionally equivalent.

**Original Python Version**: See `app.py` for the Streamlit implementation
**PHP Version**: Fully self-contained, no external dependencies required
