<script setup lang="js">
import { ref, onMounted } from "vue";
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from './ui/card';
import { Button } from './ui/button';
import { ImgComparisonSlider } from '@img-comparison-slider/vue';
import { LoaderIcon } from "lucide-vue-next";

import '@jaxtheprime/vue3-dropzone/dist/style.css'
import QualitySlider from "./QualitySlider.vue";
import DropZone from "./DropZone.vue";
import axios from "axios";

const preview = ref();

const compressedPreview = ref();
const compressedSize = ref();
const compressedPercentage = ref();

const dropzoneFile = ref();

const loading = ref();

const drop = (e) => {
    setFile(e.dataTransfer.files[0]);
};

const selectedFile = () => {
    setFile(document.querySelector(".dropzoneFile").files[0]);
};

const formatSize = (bytes) => {
    const sizes = ['Bytes', 'KB', 'MB', 'GB']
    if (bytes === 0) return '0 Byte'
    const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)))
    return Math.round(bytes / Math.pow(1024, i)) + ' ' + sizes[i]
}

const reset = () => {
    console.log('reset');
}

const setFile = (file) => {
    dropzoneFile.value = file;

    preview.value = URL.createObjectURL(file);

    uploadImage(file, 50);
}

const qualityChanged = (quality) => {
    uploadImage(dropzoneFile.value, quality);
}

let sessionId = null;

onMounted(async () => {
    loading.value = true;

    sessionId = sessionStorage.getItem('sessionID');

    if (!sessionId) {
        const response = await axios.get('/api/session');

        sessionId = response.data.session_id;

        sessionStorage.setItem('sessionID', sessionId);
    }

    loading.value = false;
});


const uploadImage = (file, quality) => {
    const formData = new FormData();
    formData.append("image", file);

    loading.value = true;

    return axios
        .post(`/api/${sessionId}/upload`, formData, {
            params: {
                quality: quality,
            },
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        .then((response) => {
            const data = response.data;

            compressedPreview.value = data.uri;
            compressedSize.value = data.size;
            compressedPercentage.value = data.percentage;
        }).finally(() => {
            loading.value = false;
        });
}

</script>

<template>
    <div v-if="loading"
        class="absolute bottom-0 left-0 right-0 top-0 flex justify-center justify-items-center items-center bg-gray-300">
        <LoaderIcon class="animate-spin" />
    </div>
    <div className="w-full max-w-6xl mx-auto p-4">
        <Card className="mb-6 relative">
            <CardHeader>
                <CardTitle className="text-center">Compress IMAGE</CardTitle>
                <CardDescription className="text-center"> Compress JPG or PNG with the best quality and compression.
                    Reduce
                    the filesize of your image at once.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <DropZone @drop.prevent="drop" @change="selectedFile" />
            </CardContent>
        </Card>

        <Card className="mb-6 relative" v-if="compressedPreview">
            <CardHeader>
                <CardTitle className="text-center truncate">
                    {{ dropzoneFile.name }}
                </CardTitle>
            </CardHeader>
            <CardContent>
                <div className="mb-8">
                    <QualitySlider @changed="qualityChanged" />
                </div>

                <div className="flex w-full mb-2 flex-col sm:flex-row">
                    <div className="flex basis-1/2 justify-center gap-1">
                        <span>Original:</span>
                        <span className="font-semibold">
                            {{ formatSize(dropzoneFile.size) }}
                        </span>
                    </div>
                    <div className="flex basis-1/2 justify-center gap-1">
                        <span>Compressed:</span>
                        <span className="font-semibold">
                            {{ formatSize(compressedSize) }} (
                            {{ compressedPercentage }}%)
                        </span>
                    </div>
                </div>

                <div className="flex justify-center">
                    <ImgComparisonSlider>
                        <img slot="first" :src="preview" alt="Preview" width="100%" />
                        <img slot="second" :src="compressedPreview" alt="Preview"
                            className="rounded object-contain w-full h-auto" />
                    </ImgComparisonSlider>
                </div>
            </CardContent>
        </Card>

        <div className="md:col-span-2 flex justify-center gap-4" v-if="dropzoneFile">
            <Button @onClick="reset" variant="outline">
                Clear
            </Button>
            <a class="inline-block px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 transition"
                :href="compressedPreview" :download="dropzoneFile.name">
                Download
            </a>
        </div>
    </div>
</template>
