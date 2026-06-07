        function showDocPreview(input) {
            const preview = document.getElementById('docPreview');
            if (input.files && input.files[0]) {
                preview.textContent = '✓ ' + input.files[0].name;
            }
        }