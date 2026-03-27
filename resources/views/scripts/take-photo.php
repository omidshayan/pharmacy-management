<script>
    const openCameraBtn = document.getElementById('openCamera');
    const overlay = document.getElementById('overlay');
    const cameraModal = document.getElementById('cameraModal');
    const video = document.getElementById('video');
    const captureBtn = document.getElementById('capture');
    const cancelBtn = document.getElementById('cancel');
    const capturedPhoto = document.getElementById('capturedPhoto');
    const photoInput = document.getElementById('photoInput');
    const captureBtnInPhoto = document.getElementById('openCamera');

    let stream;

    document.getElementById('openCamera').addEventListener('click', function(event) {
        event.preventDefault();
    });
    document.getElementById('capture').addEventListener('click', function(event) {
        event.preventDefault();
    });
    document.getElementById('cancel').addEventListener('click', function(event) {
        event.preventDefault();
    });

    openCameraBtn.addEventListener('click', async () => {
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: true
            });
            video.srcObject = stream;
            overlay.classList.add('active');
            cameraModal.classList.add('active');
        } catch (error) {
            alert('دسترسی به وب کم امکان‌پذیر نیست.');
        }
    });

    captureBtn.addEventListener('click', () => {
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');

        const width = video.videoWidth;
        const height = video.videoHeight;
        const aspectRatio = 3 / 4;

        let cropWidth, cropHeight, cropX, cropY;

        if (width / height > aspectRatio) {
            cropHeight = height;
            cropWidth = height * aspectRatio;
            cropX = (width - cropWidth) / 2;
            cropY = 0;
        } else {
            cropWidth = width;
            cropHeight = width / aspectRatio;
            cropX = 0;
            cropY = (height - cropHeight) / 2;
        }

        canvas.width = 400;
        canvas.height = 500;
        context.drawImage(video, cropX, cropY, cropWidth, cropHeight, 0, 0, canvas.width, canvas.height);

        canvas.toBlob((blob) => {
            const file = new File([blob], 'photo.png', {
                type: 'image/png'
            });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            photoInput.files = dataTransfer.files;
        });

        capturedPhoto.src = canvas.toDataURL('image/png');
        captureBtnInPhoto.style.display = 'none';
        closeCameraModal();
    });

    cancelBtn.addEventListener('click', () => {
        closeCameraModal();
    });

    function closeCameraModal() {
        if (stream) {
            const tracks = stream.getTracks();
            tracks.forEach(track => track.stop());
        }
        overlay.classList.remove('active');
        cameraModal.classList.remove('active');
    }

    capturedPhoto.addEventListener('click', async () => {
        if (!capturedPhoto.src) return;
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: true
            });
            video.srcObject = stream;
            overlay.classList.add('active');
            cameraModal.classList.add('active');
        } catch (error) {
            alert('دسترسی به وب کم امکان‌پذیر نیست.');
        }
    });
</script>