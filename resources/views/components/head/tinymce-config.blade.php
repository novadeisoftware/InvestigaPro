<div>
    
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
<script>
  tinymce.init({
    height: 620,
    elementpath: false,   // <--- ESTO BORRA EL "p"
    branding: false,      // Oculta "Powered by TinyMCE"
    promotion: false,     // Quita el botón de actualizar a premium
    menubar: false,       // Opcional: si quieres un look aún más limpio
    selector: 'textarea#myeditorinstance', // Replace this CSS selector to match the placeholder element for TinyMCE
    plugins: 'code table lists',
    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table'
  });
</script>
</div>