#  SmartHabit-MY

**AI-Driven Housing Affordability & Inclusivity Dashboard for Malaysia**

SDG XI Hackathon 2025 | Smart Mobility & Communities | SDG 11: Sustainable Cities

---

##  Project Overview

SmartHabit-MY is an intelligent dashboard that helps Malaysians find affordable and livable housing options using cutting-edge AI technologies:

- **Fuzzy Logic Engine**: Handles uncertainty in housing decisions with intelligent livability scoring
- **Machine Learning Predictor**: Random Forest model predicts fair rental prices
- **Interactive Visualization**: Beautiful dark-themed dashboard with real-time insights

---

##  Quick Start

### Installation

```bash
# Install dependencies
pip install -r requirements.txt

# Run the application
streamlit run app.py
```

The dashboard will open in your browser at `http://localhost:8501`

---

##  Key Features

### 1. **Fuzzy Livability Logic Engine** (Innovation Highlight)
- Uses fuzzy inference to assess housing livability
- Considers distance to transport and price affordability
- Outputs intelligent livability scores (0-100)

### 2. **ML Affordability Predictor**
- Random Forest model trained on Malaysian housing data
- Predicts fair rental prices based on location features
- Displays R² and MSE metrics for transparency

### 3. **Interactive Dashboard**
- User-friendly sidebar for preference input
- Real-time metric cards showing predictions
- Interactive scatter plots with highlighted recommendations
- AI-powered text recommendations

---

##  Technology Stack

- **Frontend**: Streamlit with custom CSS (Dark Theme + Neon Accents)
- **Data Processing**: Pandas, NumPy
- **Machine Learning**: Scikit-learn (Random Forest)
- **Fuzzy Logic**: Scikit-fuzzy
- **Visualization**: Plotly

---

##  Dataset

Synthetic dataset simulating 200 housing options across:
- Kuala Lumpur & Selangor locations
- Rental prices (RM 800 - 5000)
- Distance to MRT (0.5 - 15 km)
- Safety indices and amenity counts

---

##  Hackathon Judging Criteria Alignment

-  **Innovation**: Fuzzy Logic for livability assessment (unique approach)  
- **Technical Implementation**: ML + Fuzzy Logic + Interactive UI  
- **SDG Impact**: Addresses SDG 11 (Affordable Housing & Sustainable Cities)  
- **User Experience**: Modern, intuitive dark-themed dashboard  
- **Scalability**: Modular code, easy to extend with real data

---

##  Usage Example

1. Select your preferred location (e.g., Cyberjaya)
2. Set your budget (e.g., RM 2000)
3. Choose max distance to MRT (e.g., 5 km)
4. View AI recommendations with livability scores
5. Explore interactive charts and top options

---

##  Future Enhancements

- Integration with real Malaysian property APIs
- Multi-criteria decision analysis (MCDA)
- User authentication and saved preferences
- Mobile-responsive design
- Multilingual support (Bahasa Malaysia)

---

