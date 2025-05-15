document.getElementById('uploadForm').addEventListener('submit', function (event) {
    const carNumber = document.getElementById('car_number').value;
    const recordedDate = document.getElementById('recorded_date').value;
    const fileInputs = ['front_image', 'left_image', 'back_image', 'right_image', 'dashboard_image'];
    
    let errorMessages = [];
    
    // Validate text fields
    if (!carNumber) errorMessages.push('Car number is required');
    if (!recordedDate) errorMessages.push('Recorded date is required');
    
    // Validate file inputs
    fileInputs.forEach(id => {
        const input = document.getElementById(id);
        const statusElement = document.getElementById(`${id}_status`);
        
        if (!input.files || input.files.length === 0) {
            errorMessages.push(`${id.replace('_', ' ')} is required`);
            statusElement.textContent = 'This field is required';
            statusElement.style.color = 'red';
        } else {
            statusElement.textContent = '';
        }
    });
    
    if (errorMessages.length > 0) {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Missing Information',
            html: errorMessages.join('<br>'),
            confirmButtonText: 'OK'
        });
    }
});

// Toggle mobile menu
const mobileMenu = document.getElementById('mobile-menu');
const navLinks = document.querySelector('.nav-links');

mobileMenu.addEventListener('click', () => {
    navLinks.classList.toggle('active');
});

    

const dateInput = document.getElementById('recorded_date');
const today = new Date();
const formattedDate = today.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
});
dateInput.placeholder = formattedDate; // Display formatted date as placeholder

// Function to convert an image to WebP format 4/X/2025
// Updated convertToWebP function with proper preview container 5/15/2025
function convertToWebP(file, previewId, statusId) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = (event) => {
            const img = new Image();
            img.onload = () => {
                // Create a canvas element
                const canvas = document.createElement('canvas');
                canvas.width = img.width;
                canvas.height = img.height;

                // Draw the image onto the canvas
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0);

                // Convert the canvas content to WebP
                canvas.toBlob((blob) => {
                    if (blob) {
                        // Display the preview in a container
                        const preview = document.getElementById(previewId);
                        preview.innerHTML = '';
                        const container = document.createElement('div');
                        container.className = 'image-preview-container';
                        
                        const previewImg = document.createElement('img');
                        previewImg.src = URL.createObjectURL(blob);
                        previewImg.alt = 'Preview';
                        
                        container.appendChild(previewImg);
                        preview.appendChild(container);

                        // Update the status
                        const status = document.getElementById(statusId);
                        status.innerHTML = "Conversion successful!";

                        // Clean up the object URL when done
                        previewImg.onload = function() {
                            URL.revokeObjectURL(this.src);
                        };

                        // Resolve with the WebP blob
                        resolve(blob);
                    } else {
                        reject("Failed to convert image to WebP.");
                    }
                }, 'image/webp', 0.8); // 0.8 = quality (0-1)
            };
            img.onerror = () => reject("Image load error");
            img.src = event.target.result;
        };
        reader.onerror = (error) => reject(error);
        reader.readAsDataURL(file);
    });
}

// Add event listeners to file inputs
const fileInputs = [
    { id: 'front_image', preview: 'front_image_preview', status: 'front_image_status' },
    { id: 'left_image', preview: 'left_image_preview', status: 'left_image_status' },
    { id: 'back_image', preview: 'back_image_preview', status: 'back_image_status' },
    { id: 'right_image', preview: 'right_image_preview', status: 'right_image_status' },
    { id: 'dashboard_image', preview: 'dashboard_image_preview', status: 'dashboard_image_status' },
];

fileInputs.forEach((input) => {
    const fileInput = document.getElementById(input.id);
    fileInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            convertToWebP(file, input.preview, input.status)
                .then((blob) => {
                    // Replace the original file with the WebP blob
                    const webpFile = new File([blob], file.name.replace(/\.[^/.]+$/, '.webp'), { type: 'image/webp' });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(webpFile);
                    event.target.files = dataTransfer.files;
                })
                .catch((error) => {
                    console.error(error);
                    const status = document.getElementById(input.status);
                    status.innerHTML = "Conversion failed. Please try again.";
                });
        }
    });
});

// Function to handle "Take Photo" button
// Helper function to create camera modal
function createCameraModal() {
    const modal = document.createElement('div');
    modal.style.position = 'fixed';
    modal.style.top = '0';
    modal.style.left = '0';
    modal.style.width = '100%';
    modal.style.height = '100%';
    modal.style.backgroundColor = 'rgba(0,0,0,0.9)';
    modal.style.zIndex = '1000';
    modal.style.display = 'flex';
    modal.style.flexDirection = 'column';
    modal.style.alignItems = 'center';
    modal.style.justifyContent = 'center';
    return modal;
}

// Main camera capture function
async function takePhoto(inputId) {
    const statusElement = document.getElementById(`${inputId}_status`);
    statusElement.textContent = "Initializing camera...";
    statusElement.style.color = "blue";

    try {
        // First get basic access to discover capabilities
        const stream = await navigator.mediaDevices.getUserMedia({ 
            video: {
                facingMode: { ideal: "environment" }
            },
            audio: false
        });

        const track = stream.getVideoTracks()[0];
        const capabilities = track.getCapabilities();
        const settings = track.getSettings();
        
        console.log('Camera capabilities:', capabilities);
        track.stop(); // Stop this preview stream

        // Build optimized constraints
        const constraints = {
            video: {
                deviceId: { exact: settings.deviceId },
                facingMode: { ideal: "environment" },
                width: { 
                    min: 640,
                    ideal: Math.min(1920, capabilities.width?.max || 1920),
                    max: capabilities.width?.max || 1920
                },
                height: { 
                    min: 480,
                    ideal: Math.min(1080, capabilities.height?.max || 1080),
                    max: capabilities.height?.max || 1080
                },
                resizeMode: "none"
            }
        };

        // Try again with optimized constraints
        const highResStream = await navigator.mediaDevices.getUserMedia(constraints);
        const videoTrack = highResStream.getVideoTracks()[0];
        const videoSettings = videoTrack.getSettings();
        
        console.log('Actual resolution:', 
            videoSettings.width + 'x' + videoSettings.height);

        // Create and show camera UI
        const modal = createCameraModal();
        const video = document.createElement('video');
        video.style.maxWidth = '100%';
        video.style.maxHeight = '80vh';
        video.autoplay = true;
        video.playsInline = true;
        video.srcObject = highResStream;
        modal.appendChild(video);

        // Create capture button
        const captureBtn = document.createElement('button');
        captureBtn.textContent = 'Capture Photo';
        captureBtn.style.marginTop = '20px';
        captureBtn.style.padding = '10px 20px';
        captureBtn.style.backgroundColor = '#4CAF50';
        captureBtn.style.color = 'white';
        captureBtn.style.border = 'none';
        captureBtn.style.borderRadius = '4px';
        captureBtn.style.cursor = 'pointer';

        // Create cancel button
        const cancelBtn = document.createElement('button');
        cancelBtn.textContent = 'Cancel';
        cancelBtn.style.marginTop = '10px';
        cancelBtn.style.padding = '10px 20px';
        cancelBtn.style.backgroundColor = '#f44336';
        cancelBtn.style.color = 'white';
        cancelBtn.style.border = 'none';
        cancelBtn.style.borderRadius = '4px';
        cancelBtn.style.cursor = 'pointer';

        modal.appendChild(captureBtn);
        modal.appendChild(cancelBtn);
        document.body.appendChild(modal);

        statusElement.textContent = "Camera ready - point at subject";
        statusElement.style.color = "green";

        // Setup button handlers
        captureBtn.onclick = () => {
            capturePhoto(video, highResStream, inputId);
            document.body.removeChild(modal);
        };

        cancelBtn.onclick = () => {
            highResStream.getTracks().forEach(t => t.stop());
            document.body.removeChild(modal);
            statusElement.textContent = "Camera cancelled";
            statusElement.style.color = "orange";
        };

    } catch (error) {
        console.error("Camera error:", error);
        statusElement.textContent = "Error: " + error.message;
        statusElement.style.color = "red";
        
        // Fallback to device camera app
        if (confirm("Browser camera failed. Use device camera app instead?")) {
            useDeviceCameraApp(inputId);
        }
    }
}

// Photo capture handler
function capturePhoto(video, stream, inputId) {
    const statusElement = document.getElementById(`${inputId}_status`);
    statusElement.textContent = "Processing photo...";
    statusElement.style.color = "blue";

    try {
        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        stream.getTracks().forEach(track => track.stop());
        
        canvas.toBlob(blob => {
            const fileName = `photo_${new Date().getTime()}.webp`;
            const file = new File([blob], fileName, {
                type: 'image/webp',
                lastModified: Date.now()
            });
            
            processCapturedImage(inputId, file);
        }, 'image/webp', 0.95);
        
    } catch (error) {
        console.error("Capture error:", error);
        statusElement.textContent = "Capture failed: " + error.message;
        statusElement.style.color = "red";
    }
}

// Device camera app fallback
function useDeviceCameraApp(inputId) {
    const tempInput = document.createElement('input');
    tempInput.type = 'file';
    tempInput.accept = 'image/*';
    tempInput.capture = 'environment';
    
    tempInput.onchange = (e) => {
        if (e.target.files[0]) {
            processCapturedImage(inputId, e.target.files[0]);
        }
    };
    tempInput.click();
}

// Your existing WebP processor
function processCapturedImage(inputId, file) {
    const input = document.getElementById(inputId);
    const previewId = `${inputId}_preview`;
    const statusId = `${inputId}_status`;
    const statusElement = document.getElementById(statusId);

    // Skip conversion if already WebP
    if (file.type === 'image/webp') {
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        input.files = dataTransfer.files;
        updatePreview(previewId, file);
        statusElement.textContent = "Photo ready!";
        statusElement.style.color = "green";
        return;
    }

    statusElement.textContent = "Converting to WebP...";
    convertToWebP(file, previewId, statusId)
        .then((webpBlob) => {
            const webpFile = new File([webpBlob], 
                file.name.replace(/\.[^/.]+$/, '') + '.webp', 
                { type: 'image/webp' });
            
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(webpFile);
            input.files = dataTransfer.files;
            
            statusElement.textContent = "Photo processed!";
            statusElement.style.color = "green";
        })
        .catch((error) => {
            console.error("Conversion error:", error);
            statusElement.textContent = "Using original (conversion failed)";
            statusElement.style.color = "orange";
            
            // Fallback to original file
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            input.files = dataTransfer.files;
            updatePreview(previewId, file);
        });
}

// Helper to update preview
function updatePreview(previewId, file) {
    const preview = document.getElementById(previewId);
    const url = URL.createObjectURL(file);
    
    // Clear previous preview and create container
    preview.innerHTML = '';
    const container = document.createElement('div');
    container.className = 'image-preview-container';
    
    // Create image element
    const img = document.createElement('img');
    img.src = url;
    img.alt = 'Preview';
    
    // Add image to container
    container.appendChild(img);
    preview.appendChild(container);
    
    // Clean up memory
    URL.revokeObjectURL(url);
}

function viewRecord(id) {
    // Implement view functionality
    window.location.href = 'view_record.php?id=' + id;
}

function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this record?')) {
        // Implement delete functionality
        window.location.href = 'delete_record.php?id=' + id;
    }
}