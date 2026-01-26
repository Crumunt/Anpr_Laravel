"""
Image Processing Service

Handles image loading, saving, preprocessing, and manipulation operations.
"""

import os
import uuid
from datetime import datetime
from pathlib import Path

from flask import current_app
from PIL import Image
import requests


class ImageService:
    """
    Service class for image processing operations.
    """
    
    def __init__(self):
        """Initialize image service with configuration."""
        self.upload_folder = current_app.config.get('UPLOAD_FOLDER', 'storage/uploads')
        self.processed_folder = current_app.config.get('PROCESSED_FOLDER', 'storage/processed')
        
        # Ensure directories exist
        Path(self.upload_folder).mkdir(parents=True, exist_ok=True)
        Path(self.processed_folder).mkdir(parents=True, exist_ok=True)
    
    def load_image(self, image_path):
        """
        Load an image from file path.
        
        Args:
            image_path: Path to image file
            
        Returns:
            PIL.Image: Image object or None if loading fails
        """
        try:
            if not os.path.exists(image_path):
                current_app.logger.error(f"Image file not found: {image_path}")
                return None
            
            image = Image.open(image_path)
            # Convert to RGB if necessary
            if image.mode != 'RGB':
                image = image.convert('RGB')
            
            return image
        
        except Exception as e:
            current_app.logger.error(f"Error loading image: {str(e)}")
            return None
    
    def save_uploaded_image(self, file, filename=None):
        """
        Save an uploaded file to storage.
        
        Args:
            file: FileStorage object from Flask request
            filename: Optional custom filename (if None, generates UUID-based name)
            
        Returns:
            str: Path to saved image file
        """
        try:
            # Generate filename if not provided
            if not filename:
                extension = file.filename.rsplit('.', 1)[1].lower() if '.' in file.filename else 'jpg'
                filename = f"{uuid.uuid4()}.{extension}"
            
            # Create date-based subdirectory
            date_folder = datetime.now().strftime('%Y/%m/%d')
            save_dir = Path(self.upload_folder) / date_folder
            save_dir.mkdir(parents=True, exist_ok=True)
            
            # Save file
            file_path = save_dir / filename
            file.save(str(file_path))
            
            current_app.logger.info(f"Image saved: {file_path}")
            return str(file_path)
        
        except Exception as e:
            current_app.logger.error(f"Error saving uploaded image: {str(e)}")
            raise
    
    def download_image_from_url(self, image_url):
        """
        Download an image from URL and save locally.
        
        Args:
            image_url: URL of the image to download
            
        Returns:
            str: Path to downloaded image file
            
        Raises:
            ValueError: If download fails
        """
        try:
            # Download image
            response = requests.get(image_url, timeout=30, stream=True)
            response.raise_for_status()
            
            # Determine file extension from URL or Content-Type
            extension = 'jpg'
            if '.' in image_url:
                extension = image_url.rsplit('.', 1)[1].lower().split('?')[0]
            elif 'content-type' in response.headers:
                content_type = response.headers['content-type']
                if 'jpeg' in content_type or 'jpg' in content_type:
                    extension = 'jpg'
                elif 'png' in content_type:
                    extension = 'png'
            
            # Generate filename and save
            filename = f"{uuid.uuid4()}.{extension}"
            date_folder = datetime.now().strftime('%Y/%m/%d')
            save_dir = Path(self.upload_folder) / date_folder
            save_dir.mkdir(parents=True, exist_ok=True)
            
            file_path = save_dir / filename
            
            # Save downloaded image
            with open(file_path, 'wb') as f:
                for chunk in response.iter_content(chunk_size=8192):
                    f.write(chunk)
            
            current_app.logger.info(f"Image downloaded and saved: {file_path}")
            return str(file_path)
        
        except Exception as e:
            current_app.logger.error(f"Error downloading image from URL: {str(e)}")
            raise ValueError(f"Failed to download image from URL: {str(e)}")
    
    def crop_region(self, image, bbox):
        """
        Crop a region from an image.
        
        Args:
            image: PIL Image object
            bbox: Bounding box [x, y, width, height]
            
        Returns:
            PIL.Image: Cropped image
        """
        x, y, w, h = bbox
        return image.crop((x, y, x + w, y + h))
    
    def preprocess_for_ocr(self, image):
        """
        Preprocess image for OCR (grayscale, contrast enhancement, etc.).
        
        TODO: Implement image preprocessing pipeline.
        
        Args:
            image: PIL Image object
            
        Returns:
            PIL.Image: Preprocessed image
        """
        # Placeholder: Convert to grayscale
        # TODO: Add contrast enhancement, noise reduction, etc.
        if image.mode != 'L':
            return image.convert('L')
        return image
