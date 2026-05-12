<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">

      <h1 class="text-3xl font-bold text-center mb-6">
        EduMax
      </h1>

      <form class="space-y-4">

        <input
          type="email"
          placeholder="Correo"
          class="w-full border p-3 rounded-lg"
        />

        <input
          type="password"
          placeholder="Contraseña"
          class="w-full border p-3 rounded-lg"
        />

        <button
          type="button"
          class="w-full bg-blue-600 text-white p-3 rounded-lg"
        >
          Iniciar sesión
        </button>

      </form>

    </div>

  </div>
</template>

<script setup lang="ts">

</script>