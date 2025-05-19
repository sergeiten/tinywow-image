<script setup>
import { ref } from 'vue';
import { Slider } from './ui/slider';

const emit = defineEmits(['changed']);
const quality = ref([50]);

</script>

<template>
    <div>
        <div className="text-center mb-1">Quality: {{ quality[0] }}%</div>
        <Slider v-model="quality" :max="100" :step="1" v-on:value-commit="value => $emit('changed', value[0])" />
    </div>
</template>
