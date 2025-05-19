<script setup lang="ts">
import ImageUploader from '@/components/ImageUploader.vue';
</script>

<template>
    <div className="flex flex-col h-screen justify-between">
        <main className="container mx-auto p-4">
            <ImageUploader />
        </main>
        <footer className="text-center pb-4 text-sm text-muted-foreground">
            <p>All uploaded images are deleted after 1 hour</p>
            <p>&copy; Image Compressor</p>
        </footer>
    </div>
</template>
