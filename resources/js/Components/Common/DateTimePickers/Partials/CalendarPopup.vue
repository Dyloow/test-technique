<template>
    <div v-if="show" class="calendar-popup">
        <div class="calendar-header">
            <button @click="changeMonth(-1)" class="arrow-button">
                &#9664;
            </button>
            <input type="number" v-model="currentYear" @change="updateYear" class="year-input" />
            <span>{{ currentDate.format("MMMM YYYY") }}</span>
            <button @click="changeMonth(1)" class="arrow-button">
                &#9654;
            </button>
        </div>
        <div class="calendar-grid">
            <div v-for="day in days" :key="day" class="calendar-day" @click="selectDate(day)">
                <span :class="{ today: isToday(day), selected: isSelected(day) }">{{ day }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, computed } from "vue";
import moment from "moment";

const props = defineProps({
    show: Boolean,
    value: Object,
});

const emit = defineEmits(["update:value", "close"]);

const currentDate = ref(moment());
const selectedDate = ref(props.value || moment());
const currentYear = ref(currentDate.value.year());

watch(
    () => props.value,
    (newVal) => {
        selectedDate.value = newVal || moment();
        currentDate.value = selectedDate.value.clone();
        currentYear.value = currentDate.value.year();
    }
);

const selectDate = (day) => {
    const date = moment({
        year: currentDate.value.year(),
        month: currentDate.value.month(),
        day,
    });
    selectedDate.value = date;
    emit("update:value", selectedDate.value);
    emit("close");
};

const changeMonth = (direction) => {
    const newMonth = currentDate.value.clone().add(direction, "months");
    currentDate.value = newMonth;
    currentYear.value = newMonth.year();
};

const updateYear = () => {
    currentDate.value = currentDate.value.clone().year(currentYear.value);
};

const daysInMonth = computed(() => currentDate.value.daysInMonth());
const days = computed(() =>
    Array.from({ length: daysInMonth.value }, (_, i) => i + 1)
);

const isToday = (day) => {
    return moment().isSame(
        moment({
            year: currentDate.value.year(),
            month: currentDate.value.month(),
            day,
        }),
        "day"
    );
};

const isSelected = (day) => {
    return selectedDate.value.isSame(
        moment({
            year: currentDate.value.year(),
            month: currentDate.value.month(),
            day,
        }),
        "day"
    );
};
</script>

<style scoped>
.calendar-popup {
    position: absolute;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 10px;
    z-index: 1000;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.calendar-header {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.arrow-button {
    border: none;
    background: none;
    font-size: 1.5rem;
    cursor: pointer;
}

.year-input {
    width: 100px;
    text-align: center;
    margin: 0 10px;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
}

.calendar-day {
    padding: 5px;
    text-align: center;
    cursor: pointer;
}

.today {
    background-color: #f0f0f0;
}

.selected {
    background-color: #007bff;
    color: white;
    border-radius: 50%;
}
</style>
