<template>
  <div id="notfound" class="notfound">
    <h1>404</h1>
    <h2>Oops! Page introuvable</h2>
    <p>Désolé mais la page que vous recherchez n’existe pas, ont été supprimés. nom changé ou est temporairement indisponible</p>
    <a href="/">Retour à la page d’accueil</a>
  </div>
</template>

<script>
export default {
  name: "NotFound.vue"
}
</script>

<style scoped>
.notfound {
  height: 50vh;
  max-width: 560px;
  width: 100%;
  line-height: 1.1;
  margin-top: 6rem;
  margin-right: auto !important;
  margin-left: auto !important;
}

.notfound h1 {
  font-size: 65px;
  font-weight: 700;
  margin-bottom: 2rem;
  text-transform: uppercase;
}

.notfound h2 {
  text-transform: uppercase;
}

.notfound p {
  font-family: 'Nunito', sans-serif;
  color: #999fa5;
  font-weight: 400;
}
</style>
