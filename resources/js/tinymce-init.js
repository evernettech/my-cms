import tinymce from 'tinymce/tinymce';
import 'tinymce/icons/default';
import 'tinymce/themes/silver/theme';
import 'tinymce/plugins/link';
import 'tinymce/plugins/image';
import 'tinymce/plugins/media';
import 'tinymce/plugins/code';
import 'tinymce/plugins/table';
import 'tinymce/plugins/preview';
import 'tinymce/plugins/wordcount';
import 'tinymce/plugins/visualblocks';
import 'tinymce/plugins/fullscreen';
import 'tinymce/plugins/lists';

window.tinymce = tinymce;

// Skins (optional)
import 'tinymce/skins/ui/oxide/skin.min.css';

document.addEventListener("DOMContentLoaded", function () {
    tinymce.init({

        base_url: '/tinymce',  // public URL base where assets live
        skin_url: '/tinymce/skins/ui/oxide',

        license_key: 'gpl',

        selector: 'textarea#content',
        height: 500,
        menubar: true,
        branding: false,
        mobile: {
            menubar: false
        },

        images_upload_url: '{{ route("tinymce.image-upload") }}',
        automatic_uploads: true,
        images_upload_credentials: true,
        images_reuse_filename: true,

        images_upload_handler: function (blobInfo) {
            return new Promise(function (resolve, reject) {
                const xhr = new XMLHttpRequest();
                xhr.withCredentials = true;
                xhr.open('POST', '{{ route("tinymce.image-upload") }}');

                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                xhr.onload = function () {
                    if (xhr.status !== 200) {
                        reject('HTTP Error: ' + xhr.status);
                        return;
                    }

                    let json;
                    try {
                        json = JSON.parse(xhr.responseText);
                    } catch (e) {
                        reject('Invalid JSON: ' + xhr.responseText);
                        return;
                    }

                    if (!json || typeof json.location !== 'string') {
                        reject('Invalid response structure');
                        return;
                    }

                    resolve(decodeURIComponent(json.location.trim()));
                };

                xhr.onerror = function () {
                    reject('Network error');
                };

                const formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                xhr.send(formData);
            });
        },
        plugins: [
            'lists',         // bullet and numbered lists
            'link',          // for inserting hyperlinks
            'image',         // for uploading images
            'media',         // for embedding YouTube or MP3s
            'code',          // show/edit HTML
            'table',         // add tables
            'preview',       // preview content
            'wordcount',     // display word count
            'visualblocks',  // show block elements (handy for structure)
            'fullscreen'     // full screen mode
        ],
        toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | table | code preview fullscreen',
    });

    const form = document.querySelector('form'); // or be more specific if needed

    if (!form) return; // just in case

    form.addEventListener('submit', (event) => {
        tinymce.triggerSave(); // Sync TinyMCE content to textarea

        const content = document.getElementById('content').value.trim();
        const title = document.getElementById('title').value.trim();

        const showError = (message) => {
            tinymce.activeEditor.notificationManager.open({
                text: message,
                type: 'error',
                timeout: 4000
            });
        };

        if (!title) {
            event.preventDefault(); // stop form submit
            showError("Title is required!");
            return false;
        }

        if (!content) {
            event.preventDefault();
            showError("Content is required!");
            return false;
        }

        // If everything's good, let form submit normally
        return true;
    });

    

});