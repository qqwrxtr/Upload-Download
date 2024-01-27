document.addEventListener('DOMContentLoaded', function () {
    const uploadForm = document.getElementById('upload-form');
    const uploadButton = document.getElementById('upload-button');
    const fileUploadInput = document.querySelector('.file-upload-input');
    const progressBar = document.querySelector('.progress');

    uploadButton.addEventListener('click', function () {
        fileUploadInput.click();
    });

    fileUploadInput.addEventListener('change', function () {
        const files = this.files;
        if (files.length > 0) {
            if (files.length <= 4) {
                uploadFiles(files);
            } else {
                alert('You can upload a maximum of 4 files.');
                fileUploadInput.value = '';
            }
        }
    });

    function uploadFiles(files) {
        const formData = new FormData();

        for (let i = 0; i < files.length; i++) {
            formData.append('fileToUpload[]', files[i]);
        }

        fetch('upload.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const imageContainer = document.querySelector('.download');
                data.images.forEach(imageHtml => {
                    imageContainer.innerHTML += imageHtml;
                });

                fileUploadInput.value = '';
                progressBar.style.width = '0%';
            } else {
                alert('Upload failed. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
});
