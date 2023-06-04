<template>
    <div>
        <label for="colorPicker" class="form-label ms-1">{{ label }}</label>
        <div class="card color-picker" :style="`background-color: ${color};`">
            <div class="color-picker-bg">
                <input v-model="currentColor" type="color" class="w-100 h-100 opacity-0" id="colorPicker" @input="updateColor($event.target.value)">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ColorPicker",
    props: {
        label: {
            type: String,
            default: "Цвет",
        },
        color: {
            type: String,
            default: "#000000",
        },
    },

    data() {
        return {
            currentColor: this.color,
        }
    },

    methods: {
        updateColor(color) {
            this.currentColor = color
            this.$emit("input", color)
        },
    },
}
</script>

<style scoped>

.color-picker-bg {
    height: 120px;
}
#colorPicker {
    cursor: pointer;
}

</style>
