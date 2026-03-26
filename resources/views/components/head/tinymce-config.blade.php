<div>
    <script src="https://cdn.tiny.cloud/1/g65hxgamvteugh8dkjisxb1cmfvk0hrzcjixkntcw2mhxt9c/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
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