# 🚀 Google Colab Setup Guide

## How to Use the SmartHabit-MY Training Notebook

---

## 📋 Quick Start

### Option 1: Upload to Google Colab (Recommended)

1. **Go to Google Colab**
   - Visit: https://colab.research.google.com/

2. **Upload the Notebook**
   - Click `File` → `Upload notebook`
   - Select `SmartHabit_MY_Training.ipynb` from this folder
   - Or drag and drop the file

3. **Enable GPU (Optional but Recommended)**
   - Click `Runtime` → `Change runtime type`
   - Select `GPU` under Hardware accelerator
   - Click `Save`

4. **Run All Cells**
   - Click `Runtime` → `Run all`
   - Or press `Ctrl+F9` (Windows) / `Cmd+F9` (Mac)

5. **Wait for Training**
   - First cell installs packages (~2 minutes)
   - Model training takes ~5 minutes
   - Total runtime: ~10 minutes

---

### Option 2: Open from GitHub (If Uploaded)

1. **Direct Link**
   ```
   https://colab.research.google.com/github/YOUR_USERNAME/smarthabit-my/blob/main/firstprototype/SmartHabit_MY_Training.ipynb
   ```

2. **Or use Colab's GitHub Integration**
   - Go to https://colab.research.google.com/
   - Click `File` → `Open notebook`
   - Select `GitHub` tab
   - Enter your repository URL
   - Select the notebook

---

## 📊 What the Notebook Does

### 1. Data Generation
- Creates 1000 synthetic housing records
- Malaysian locations (KL & Selangor)
- Realistic price distributions

### 2. Random Forest Training
- 100 decision trees
- Feature importance analysis
- R² score > 0.85

### 3. Neural Network Training
- TensorFlow/Keras model
- 4 layers with dropout
- 50 epochs training
- Validation split

### 4. Model Comparison
- Side-by-side metrics
- Visualization of predictions
- Performance analysis

### 5. HuggingFace NLP
- Sentiment analysis on property descriptions
- DistilBERT model
- Confidence scoring

### 6. Model Export
- Saves trained models
- Ready for production deployment
- Downloadable files

---

## 📥 Download Trained Models

After running the notebook:

1. **Find the Files Panel**
   - Click the folder icon on the left sidebar

2. **Download Models**
   - `rf_housing_model.pkl` - Random Forest model
   - `nn_housing_model.h5` - Neural Network model
   - `feature_scaler.pkl` - Feature normalization

3. **Use in Production**
   ```python
   import joblib
   import tensorflow as tf
   
   # Load models
   rf_model = joblib.load('rf_housing_model.pkl')
   nn_model = tf.keras.models.load_model('nn_housing_model.h5')
   scaler = joblib.load('feature_scaler.pkl')
   
   # Make predictions
   features = [[5.0, 12, 8.5, 5, 1200]]  # distance, amenities, safety, age, sqft
   features_scaled = scaler.transform(features)
   
   rf_prediction = rf_model.predict(features)
   nn_prediction = nn_model.predict(features_scaled)
   ```

---

## 🔧 Troubleshooting

### Issue: Package Installation Fails
**Solution**: 
```python
# Run this in a cell
!pip install --upgrade pip
!pip install -q tensorflow scikit-learn scikit-fuzzy plotly pandas numpy transformers torch
```

### Issue: GPU Not Available
**Solution**: 
- Go to `Runtime` → `Change runtime type`
- Select `GPU` under Hardware accelerator
- If GPU quota exceeded, use CPU (will be slower)

### Issue: HuggingFace Model Download Slow
**Solution**:
```python
# Use a smaller model
from transformers import pipeline
sentiment_analyzer = pipeline("sentiment-analysis", model="distilbert-base-uncased-finetuned-sst-2-english")
```

### Issue: Out of Memory
**Solution**:
- Reduce batch size: `batch_size=16` instead of `32`
- Reduce dataset size: `generate_malaysia_housing_data(500)` instead of `1000`

---

## 📈 Expected Results

### Random Forest:
- **R² Score**: 0.85 - 0.92
- **MSE**: 10,000 - 15,000
- **MAE**: 80 - 120 RM

### Neural Network:
- **R² Score**: 0.87 - 0.94
- **MSE**: 8,000 - 12,000
- **MAE**: 70 - 110 RM

### Training Time:
- **Random Forest**: ~30 seconds
- **Neural Network**: ~3-5 minutes (GPU) / ~10-15 minutes (CPU)
- **HuggingFace**: ~1 minute (first run downloads model)

---

## 🎯 Customization Options

### Change Dataset Size:
```python
df = generate_malaysia_housing_data(2000)  # Default: 1000
```

### Adjust Neural Network Architecture:
```python
model = keras.Sequential([
    keras.layers.Dense(128, activation='relu'),  # Increase neurons
    keras.layers.Dropout(0.3),                   # Increase dropout
    keras.layers.Dense(64, activation='relu'),
    keras.layers.Dense(1)
])
```

### Train for More Epochs:
```python
history = model.fit(
    X_train_scaled, y_train,
    epochs=100,  # Default: 50
    batch_size=16
)
```

### Use Different HuggingFace Models:
```python
# For Malaysian context (multilingual)
sentiment_analyzer = pipeline("sentiment-analysis", model="nlptown/bert-base-multilingual-uncased-sentiment")
```

---

## 💾 Save to Google Drive

To save models permanently:

```python
# Mount Google Drive
from google.colab import drive
drive.mount('/content/drive')

# Save models to Drive
import joblib
joblib.dump(rf_model, '/content/drive/MyDrive/smarthabit/rf_model.pkl')
model.save('/content/drive/MyDrive/smarthabit/nn_model.h5')
```

---

## 📚 Additional Resources

### TensorFlow Documentation:
- https://www.tensorflow.org/tutorials

### scikit-learn Guide:
- https://scikit-learn.org/stable/user_guide.html

### HuggingFace Models:
- https://huggingface.co/models

### Colab Tips:
- https://colab.research.google.com/notebooks/basic_features_overview.ipynb

---

## 🏆 For Hackathon Judges

This notebook demonstrates:

✅ **TensorFlow/Keras** - Deep learning implementation  
✅ **scikit-learn** - Traditional ML (Random Forest)  
✅ **HuggingFace** - NLP sentiment analysis  
✅ **Google Colab** - Cloud-based training  
✅ **Model Comparison** - Multiple approaches  
✅ **Production Ready** - Exportable models  

**Total Training Time**: ~10 minutes  
**GPU Recommended**: Yes (but works on CPU)  
**Cost**: Free (Google Colab)

---

## 📧 Support

If you encounter issues:
1. Check the Troubleshooting section above
2. Restart runtime: `Runtime` → `Restart runtime`
3. Clear outputs: `Edit` → `Clear all outputs`
4. Re-run all cells

---

**Happy Training!** 🚀

*Last Updated: November 28, 2025*
