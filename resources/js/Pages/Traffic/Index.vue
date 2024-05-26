<script setup lang="ts">
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { client } from "@gradio/client";
import {onBeforeMount, onMounted} from "vue";

interface TrafficImage {
    CameraID: string,
    ImageLink: string,
    name: string,
}

const props = defineProps<{
    status?: string;
    trafficImages: TrafficImage[];
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
    });
};

// onBeforeMount(async () => {
//     //todo: to replace [0]
//     console.log(props.trafficImages)
//     // const image = await fetch(props.trafficImages.ImageLink);
//     const app = await client("https://nasty-crabs-beg.loca.lt/");
//     const result = await app.predict("/add_text", [
//         "Are there many cars?", // string  in 'parameter_3' Textbox component
//         props.trafficImages.ImageLink, 	// blob in 'parameter_11' Image component
//         //"Crop", // string  in 'Preprocess for non-square image' Radio component
//     ]);
//     console.log(result)
//
// });

//todo: connect this to backend llm
const mockedState = [1, 0];
const runModel = (idx: number) => {
    if(mockedState[idx]){
        return 'High Traffic';
    }
    return 'Low Traffic';
}

</script>

<template>
    <GuestLayout>
        <Head title="Traffic Vision" />


        <div class="flex gap-4 rounded-md">
            <div class="bg-white" v-for="(trafficImage, idx) in trafficImages" >


                <div class="mt-4">
                    <InputLabel class="text-2xl font-bold" for="password" :value="trafficImage.name" />

                </div>
                <img :src="'data:image/jpg;base64,'+trafficImage.ImageLink">


                </img>
                <p class="text-3xl">{{runModel(idx)}}</p>


            </div>
        </div>


    </GuestLayout>
</template>
