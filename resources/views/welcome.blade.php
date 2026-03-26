<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hola Mundo con Vue</title>
    <!-- Importamos Vue 3 desde CDN -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
</head>
<body>
    <div id="app">
        @{{ mensaje }}
    </div>

    <script>
        const { createApp } = Vue;

        createApp({
            data() {
                return {
                    mensaje: "¡Hola mundo!"
                };
            }
        }).mount('#app');
    </script>
</body>
</html>