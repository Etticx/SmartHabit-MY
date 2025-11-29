# 🏘️ SmartHabit-MY: Complete Technical Documentation

## AI-Driven Housing Affordability & Inclusivity Dashboard for Malaysia

**SDG XI Hackathon 2025 | Smart Mobility & Communities**

---

## 📑 Table of Contents

1. [Project Overview](#project-overview)
2. [Problem Statement](#problem-statement)
3. [Solution Architecture](#solution-architecture)
4. [AI & Machine Learning](#ai--machine-learning)
5. [Fuzzy Logic System](#fuzzy-logic-system)
6. [Geospatial Integration](#geospatial-integration)
7. [Data Sources](#data-sources)
8. [Technical Implementation](#technical-implementation)
9. [User Interface](#user-interface)
10. [Deployment](#deployment)
11. [SDG Alignment](#sdg-alignment)
12. [Innovation Highlights](#innovation-highlights)
13. [Future Roadmap](#future-roadmap)

---


## 1. Project Overview

### 1.1 What is SmartHabit-MY?

SmartHabit-MY is an intelligent web-based dashboard that helps Malaysians find affordable and livable housing options using cutting-edge artificial intelligence, fuzzy logic, and geospatial technologies. The system analyzes multiple factors including rental prices, proximity to public transport (MRT/LRT), safety indices, and available amenities to provide personalized housing recommendations.

### 1.2 Key Features

- **AI-Powered Price Prediction**: Machine learning models predict fair rental prices
- **Fuzzy Logic Livability Scoring**: Handles uncertainty in housing decisions with intelligent scoring (0-100)
- **Interactive Geospatial Map**: Real-time visualization using Leaflet.js and OpenStreetMap
- **Real Data Integration**: 500 actual properties from Kaggle (19,992 total available)
- **Multi-Criteria Filtering**: Budget, distance, amenities, location preferences
- **Smart Recommendations**: AI-generated personalized suggestions

### 1.3 Target Users

- **Budget-Conscious Students**: Finding affordable housing near universities
- **Young Professionals**: Seeking convenient locations near MRT
- **Families**: Looking for safe neighborhoods with good amenities
- **Expats**: Navigating Malaysian housing market
- **Property Seekers**: Anyone looking for rental properties in KL & Selangor

### 1.4 Geographic Scope

- **Primary**: Kuala Lumpur & Selangor
- **Locations**: 29 areas including KLCC, Bangsar, Mont Kiara, Cheras, Cyberjaya, etc.
- **Coverage**: 500 processed properties (expandable to 19,992)

---


## 2. Problem Statement

### 2.1 Housing Affordability Crisis in Malaysia

Malaysia faces significant housing affordability challenges:

- **Rising Rental Costs**: Prices increasing faster than income growth
- **Information Asymmetry**: Difficult to determine fair market prices
- **Location Complexity**: Hard to balance affordability with convenience
- **Transport Accessibility**: Need proximity to MRT/LRT for sustainable living
- **Safety Concerns**: Varying safety levels across neighborhoods
- **Decision Overload**: Too many factors to consider manually

### 2.2 Current Limitations

Existing property platforms lack:

- **Intelligent Recommendations**: No AI-powered suggestions
- **Livability Assessment**: No holistic quality-of-life scoring
- **Price Fairness**: No ML-based price prediction
- **Multi-Criteria Analysis**: Limited filtering options
- **Geospatial Visualization**: Poor map integration
- **Uncertainty Handling**: Binary yes/no decisions only

### 2.3 Our Solution

SmartHabit-MY addresses these gaps by:

1. **AI Price Prediction**: Tells you if a property is fairly priced
2. **Fuzzy Livability Scoring**: Handles "somewhat close" or "moderately expensive" scenarios
3. **Interactive Mapping**: Visual understanding of locations
4. **Smart Filtering**: Multi-criteria decision support
5. **Real Data**: Actual market listings from Kaggle/Mudah.my
6. **Personalization**: Recommendations based on your preferences

---


## 3. Solution Architecture

### 3.1 System Architecture

```
┌─────────────────────────────────────────────────────────┐
│                    User Interface Layer                  │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │
│  │   Sidebar    │  │  Interactive │  │    Charts    │  │
│  │   Filters    │  │     Map      │  │  (Plotly.js) │  │
│  └──────────────┘  └──────────────┘  └──────────────┘  │
└─────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────┐
│                 Application Logic Layer                  │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │
│  │  Data Filter │  │   Session    │  │  Form Handle │  │
│  │    (PHP)     │  │  Management  │  │    (POST)    │  │
│  └──────────────┘  └──────────────┘  └──────────────┘  │
└─────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────┐
│                   AI/ML Processing Layer                 │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │
│  │ Fuzzy Logic  │  │  ML Price    │  │   Amenity    │  │
│  │  Livability  │  │  Predictor   │  │   Counter    │  │
│  └──────────────┘  └──────────────┘  └──────────────┘  │
└─────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────┐
│                      Data Layer                          │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │
│  │ Kaggle CSV   │  │  Processed   │  │  Coordinate  │  │
│  │  (19,992)    │  │  Data (500)  │  │   Mapping    │  │
│  └──────────────┘  └──────────────┘  └──────────────┘  │
└─────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────┐
│                   External Services                      │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │
│  │ OpenStreetMap│  │  Leaflet.js  │  │  Plotly.js   │  │
│  │   (Tiles)    │  │   (Mapping)  │  │   (Charts)   │  │
│  └──────────────┘  └──────────────┘  └──────────────┘  │
└─────────────────────────────────────────────────────────┘
```

### 3.2 Technology Stack

#### Frontend:
- **HTML5/CSS3**: Modern responsive design
- **JavaScript (ES6+)**: Interactive functionality
- **Leaflet.js 1.9.4**: Interactive mapping
- **Plotly.js 2.27.0**: Data visualization
- **OpenStreetMap**: Map tiles and geospatial data

#### Backend:
- **PHP 7.4+**: Server-side logic
- **Apache**: Web server (XAMPP)
- **CSV**: Data storage (scalable to MySQL)

#### AI/ML:
- **TensorFlow/Keras**: Deep learning (training)
- **scikit-learn**: Random Forest, preprocessing
- **HuggingFace Transformers**: NLP sentiment analysis
- **Custom Fuzzy Logic**: PHP implementation

#### Data:
- **Kaggle**: mudah-apartment-kl-selangor dataset
- **Mudah.my**: Original property listings
- **OpenStreetMap**: GPS coordinates
- **Google Maps**: Distance validation

### 3.3 Data Flow

1. **User Input** → Sidebar form (budget, distance, amenities, location)
2. **Form Submission** → PHP POST handler
3. **Data Loading** → CSV file read (500 properties)
4. **Filtering** → Multi-criteria filtering based on preferences
5. **AI Processing** → Fuzzy logic + ML prediction
6. **Sorting** → Best match identification
7. **Visualization** → Map markers + charts
8. **Recommendation** → AI-generated text
9. **Display** → Rendered HTML response

---
 
 
 ## 4. AI & Machine Learning
 
 ### 4.1 Overview
 
 SmartHabit-MY uses a practical multi-model approach for price prediction and decision support:
 
 - **Random Forest Regressor** for fair rental price estimation during prototyping (`app.py:181`)
 - **Simplified regression** for production PHP scoring (`includes/ml_predictor.php:7`)
 - **Fuzzy inference** for livability scoring that complements ML with human-like reasoning (`app.py:123`, `includes/fuzzy_logic.php:7`)
 
 ### 4.2 Features and Inputs
 
 - **Features**: `Distance_to_MRT`, `Amenity_Count`, `Safety_Index` (`app.py:183`)
 - **Target**: `Rental_Price`
 - **Performance metrics**: R² and MSE shown in the UI (`app.py:249`)
 
 ### 4.3 Training Pipeline (Colab)
 
 - **Notebook**: `SmartHabit_MY_Training.ipynb` trains multiple models (Random Forest, Neural Network, NLP) and compares results
 - **Libraries**: TensorFlow/Keras, scikit-learn, HuggingFace Transformers
 - **Exports**: Coefficients and model insights used to configure the PHP predictor (`includes/ml_predictor.php:7`)
 
 ### 4.4 In-App Prediction Flow
 
 - Python: `train_ml_model(df)` trains a Random Forest and returns `model, mse, r2` (`app.py:181`)
 - PHP: `predictRentalPrice(distance, amenities, safety)` computes a fair price using calibrated coefficients (`includes/ml_predictor.php:7`)
 - UI displays metrics and uses the prediction to inform recommendations (`index.php:161`)
 
 
 ## 5. Fuzzy Logic System
 
 ### 5.1 Purpose
 
 Fuzzy logic converts imprecise preferences (e.g., “somewhat close”, “moderately expensive”) into an interpretable **livability score (0–100)**.
 
 ### 5.2 Variables and Membership Functions
 
 - **Antecedents**: `distance`, `price`
 - **Consequent**: `livability`
 - **Memberships**:
   - Distance: `close`, `medium`, `far` (`app.py:133`)
   - Price: `affordable`, `moderate`, `expensive` (`app.py:139`)
   - Livability: `poor`, `average`, `excellent` (`app.py:144`)
 
 Python implementation uses `scikit-fuzzy` to define triangular MFs and a control system (`app.py:123`). PHP mirrors this with custom `trimf` and weighted-average defuzzification (`includes/fuzzy_logic.php:51`).
 
 ### 5.3 Rules and Inference
 
 - 9 rules combine distance and price (e.g., close + affordable → excellent) (`app.py:149`, `includes/fuzzy_logic.php:18`)
 - AND aggregation via min; defuzzification via control system or weighted average
 - `calculate_livability_score(distance, price)` orchestrates inference (`app.py:165`, `includes/fuzzy_logic.php:7`)
 
 ### 5.4 Integration in UI
 
 - Score shown alongside predictions and recommendation details (`index.php:161`)
 - Used to rank and highlight the best match in visuals
 
 
 ## 6. Geospatial Integration
 
 ### 6.1 Libraries
 
 - **Leaflet.js** for map rendering (`assets/script.js:9`)
 - **OpenStreetMap** for basemap tiles (`assets/script.js:14`)
 
 ### 6.2 Map Features
 
 - Centered on Greater KL with custom neon-styled markers (`assets/script.js:20`)
 - Distinguishes all options, filtered matches, and the best match (`assets/script.js:38`, `assets/script.js:67`)
 - Rich popups with property attributes (`assets/script.js:54`)
 - Legend and responsive behavior (`assets/script.js:90`)
 
 ### 6.3 Coordinates and Data
 
 - Real coordinates for 10 locations; synthetic jitter for variety (`includes/data.php:58`)
 - CSV support for processed data if available (`includes/data.php:21`)
 
 
 ## 7. Data Sources
 
 ### 7.1 Primary
 
 - **Kaggle**: mudah-apartment KL & Selangor dataset (19,992 listings)
 - **Processed CSV**: subset of ~500 curated entries (`includes/data.php:8`)
 
 ### 7.2 Supplementary
 
 - **OpenStreetMap**: geospatial tiles and coordinates
 - **Government portals**: DOSM, DBKL, JPPH for enrichment (see `DATA_SOURCES.md`)
 - **Mudah.my**: original listing context
 
 ### 7.3 Data Handling
 
 - Fallback to synthetic generation when CSV absent (`includes/data.php:57`)
 - Filtering by budget, distance, amenities, and location (`includes/data.php:114`)
 
 
 ## 8. Technical Implementation
 
 ### 8.1 Backend (PHP)
 
 - `index.php` orchestrates form handling, filtering, AI scoring, and rendering (`index.php:161`)
 - `includes/data.php` loads or synthesizes geospatial property data (`includes/data.php:6`)
 - `includes/fuzzy_logic.php` computes livability (`includes/fuzzy_logic.php:7`)
 - `includes/ml_predictor.php` predicts fair rental price (`includes/ml_predictor.php:7`)
 
 ### 8.2 Frontend (JS/CSS)
 
 - `assets/script.js` renders maps and charts (`assets/script.js:9`, `assets/script.js:117`)
 - `assets/style.css` delivers a dark, neon-accented theme
 - Plotly scatter: price vs distance, sized by amenities, colored by safety (`assets/script.js:116`)
 
 ### 8.3 Python (Streamlit)
 
 - `generate_malaysia_housing_data()` creates synthetic training data (`app.py:87`)
 - `create_fuzzy_system()` and `calculate_livability_score()` handle livability (`app.py:123`, `app.py:165`)
 - `train_ml_model()` trains and evaluates Random Forest (`app.py:181`)
 
 
 ## 9. User Interface
 
 - Sidebar preferences: location, budget, distance, amenities (`app.py:219`)
 - Real-time metrics: R², MSE, predicted price, livability
 - Interactive map with highlighted best match and detailed popups (`assets/script.js:67`)
 - Plotly scatter visualizing trade-offs and top options (`assets/script.js:116`)
 - Tables and recommendation text illustrated in the PHP UI (`index.php:161`)
 
 
 ## 10. Deployment
 
 ### 10.1 Python (Streamlit)
 
 - Install deps: `pip install -r requirements.txt` (`requirements.txt:1`)
 - Run: `streamlit run app.py` → `http://localhost:8501` (`README.md:18`)
 
 ### 10.2 PHP (XAMPP)
 
 - Place the project under `htdocs` and start Apache
 - Open `http://localhost/SDG_Prelim_1/firstprototype/index.php`
 - Ensure internet for CDN assets (Leaflet, Plotly)
 
 ### 10.3 Colab Training
 
 - Upload `SmartHabit_MY_Training.ipynb`
 - Enable GPU; run cells to train and export insights
 
 
 ## 11. SDG Alignment
 
 - **SDG 11 – Sustainable Cities & Communities**
 - Promotes affordability, inclusivity, and public transport proximity
 - Reduces car dependency and supports equitable urban planning
 - Transparent AI metrics and interpretable scores enhance trust and adoption
 
 
 ## 12. Innovation Highlights
 
 - **Fuzzy Livability Engine** for uncertainty-aware scoring (`app.py:123`, `includes/fuzzy_logic.php:7`)
 - **Multi-Model AI** combining ML and fuzzy logic for robust decisions
 - **Interactive Geospatial** mapping with real Malaysian coordinates (`assets/script.js:9`)
 - **Modern UX**: dark theme, neon accents, responsive charts and maps
 
 
 ## 13. Future Roadmap
 
 - Integrate real property APIs; live market ingestion
 - Persist data in MySQL with historical trends
 - Expand criteria: commute times, school quality, flood risk
 - Personalization: saved profiles, recommendations history
 - Fairness and privacy audits; bias monitoring
 - Multilingual support: Bahasa Malaysia and Chinese
 - Mobile-first responsive enhancements and PWA packaging
 
 ---
 
 This documentation consolidates the app’s purpose, architecture, AI/ML, fuzzy logic, geospatial integration, data strategy, and deployment, with direct references to source code for rapid navigation and verification.
