<template>
    <div class="main-container min-vh-100 d-flex flex-column">
        <main class="flex-grow-1 py-4 py-md-5">
            <div class="container py-2 py-md-5 mt-5">
                <router-link to="/wedding-appointment" class="text-white text-decoration-none fw-bold">
                    <span class="me-2">&larr;</span> Back
                </router-link>

                <div class="row justify-content-center text-center mb-4 mt-2">
                    <div class="col-lg-9 col-xl-8">
                        <span class="badge bg-primary rounded-pill px-4 py-2 mb-3 shadow-sm text-uppercase fw-bold">
                            Step 1: Select Date
                        </span>
                        <h1 class="display-5 display-md-4 fw-bolder text-white mb-2 text-shadow-heavy">
                            Civil Wedding Calendar
                        </h1>
                        <p class="text-white fw-medium opacity-75 d-none d-md-block">
                            Ceremonies are held every 2nd and 4th Monday of the month.
                        </p>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-xl-10">
                        <div class="card border-0 shadow-lg glass-card overflow-hidden">

                            <div class="calendar-header p-3 p-md-4 border-bottom bg-dark bg-opacity-10">
                                <div
                                    class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                                    <div class="d-flex align-items-center gap-2 order-2 order-md-1">
                                        <button @click="changeYear(-1)"
                                            class="btn btn-sm btn-dark shadow-sm rounded-pill px-3">
                                            <i class="bi bi-chevron-double-left me-1"></i>Year
                                        </button>
                                        <button @click="changeMonth(-1)"
                                            class="btn btn-primary shadow-sm rounded-circle p-2 d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <i class="bi bi-chevron-left"></i>
                                        </button>
                                    </div>

                                    <div class="text-center order-1 order-md-2">
                                        <h2 class="fw-bold text-dark mb-0 text-uppercase letter-spacing-2 fs-4 fs-md-3">
                                            {{ currentMonthName }} <span class="text-primary">{{ currentYear }}</span>
                                        </h2>
                                    </div>

                                    <div class="d-flex align-items-center gap-2 order-3 order-md-3">
                                        <button @click="changeMonth(1)"
                                            class="btn btn-primary shadow-sm rounded-circle p-2 d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <i class="bi bi-chevron-right"></i>
                                        </button>
                                        <button @click="changeYear(1)"
                                            class="btn btn-sm btn-dark shadow-sm rounded-pill px-3">
                                            Year<i class="bi bi-chevron-double-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="d-grid calendar-grid border-bottom bg-white bg-opacity-75">
                                    <div v-for="day in weekDays" :key="day"
                                        class="py-2 py-md-3 text-center fw-bold text-primary small text-uppercase">
                                        {{ day }}
                                    </div>
                                </div>

                                <div class="d-grid calendar-grid bg-white bg-opacity-25">
                                    <div v-for="blank in firstDayPadding" :key="'blank-' + blank"
                                        class="calendar-day padding d-none d-md-flex"></div>

                                    <div v-for="date in daysInMonth" :key="date" class="calendar-day" :class="{
                                        'selected': isSelected(date),
                                        'today': isToday(date),
                                        'disabled': isPast(date),
                                        'full-day': isDateFull(date) && !isPast(date) && isAllowedDate(date),
                                        'available-monday': isAllowedDate(date) && !isPastBasic(date) && !isDateFull(date)
                                    }" @click="toggleDate(date)">
                                        <span class="day-number">{{ date }}</span>
                                        <div v-if="isAllowedDate(date) && !isPastBasic(date)" class="slot-indicator"
                                            :class="{ 'full': isDateFull(date) }">
                                            {{ getWeddingCount(date) }}/{{ maxWeddingsPerDay }}
                                        </div>
                                        <div v-if="isSelected(date)" class="selection-mark animate-pop">
                                            <i class="bi bi-heart-fill"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="p-3 p-md-4 bg-white bg-opacity-75 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                                <div class="selected-info text-center text-md-start">
                                    <transition name="fade" mode="out-in">
                                        <div v-if="selectedDate" :key="formattedSelectedDate">
                                            <span class="text-muted small d-block mb-1">Appointment Date:</span>
                                            <span class="text-dark fw-bold fs-5">{{ formattedSelectedDate }}</span>
                                        </div>
                                        <span v-else class="text-muted italic small">
                                            Please select a scheduled Monday (2nd or 4th of the month)
                                        </span>
                                    </transition>
                                </div>

                                <div class="d-flex w-100 w-md-auto gap-2">
                                    <button v-if="selectedDate" @click="selectedDate = null"
                                        class="btn btn-outline-danger rounded-pill px-3 px-md-4 fw-bold">
                                        Clear
                                    </button>
                                    <button class="btn btn-primary rounded-pill px-4 px-md-5 fw-bold shadow flex-grow-1"
                                        :disabled="!selectedDate" @click="proceedToStep2">
                                        Confirm & Proceed
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
import api from '../../../controller/api';
import Swal from 'sweetalert2';

export default {
    name: 'WeddingCalendar',
    data() {
        return {
            config: { siteName: "LCRO Abuyog" },
            currentDate: new Date(),
            selectedDate: null,
            maxWeddingsPerDay: 3,
            weddingCountsByDate: {},
            weekDays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        };
    },
    mounted() {
        this.fetchCivilWeddingCounts();
    },
    computed: {
        currentMonth() { return this.currentDate.getMonth(); },
        currentYear() { return this.currentDate.getFullYear(); },
        currentMonthName() {
            return new Intl.DateTimeFormat('en-US', { month: 'long' }).format(this.currentDate);
        },
        daysInMonth() {
            return new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
        },
        firstDayPadding() {
            return new Date(this.currentYear, this.currentMonth, 1).getDay();
        },
        formattedSelectedDate() {
            if (!this.selectedDate) return '';
            return new Intl.DateTimeFormat('en-US', { dateStyle: 'full' }).format(this.selectedDate);
        }
    },
    methods: {
        async fetchCivilWeddingCounts() {
            try {
                const response = await api.get('/Appointments/availability', {
                    params: {
                        appointment_type: 'Civil Wedding',
                        year: this.currentYear,
                        month: this.currentMonth + 1
                    }
                });

                this.weddingCountsByDate = response?.data?.counts || {};

                if (this.selectedDate) {
                    const selectedDay = this.selectedDate.getDate();
                    if (this.isDateFull(selectedDay)) {
                        this.selectedDate = null;
                    }
                }
            } catch (error) {
                this.weddingCountsByDate = {};
            }
        },
        getDateKey(date) {
            const target = new Date(this.currentYear, this.currentMonth, date);
            const year = target.getFullYear();
            const month = String(target.getMonth() + 1).padStart(2, '0');
            const day = String(target.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        },
        getWeddingCount(date) {
            return this.weddingCountsByDate[this.getDateKey(date)] || 0;
        },
        isDateFull(date) {
            return this.getWeddingCount(date) >= this.maxWeddingsPerDay;
        },
        changeMonth(step) {
            this.currentDate = new Date(this.currentYear, this.currentMonth + step, 1);
            this.fetchCivilWeddingCounts();
        },
        changeYear(step) {
            this.currentDate = new Date(this.currentYear + step, this.currentMonth, 1);
            this.fetchCivilWeddingCounts();
        },
        // ALGORITHM: Checks if the date is the 2nd or 4th Monday
        isAllowedDate(date) {
            const checkDate = new Date(this.currentYear, this.currentMonth, date);
            // 1 = Monday
            if (checkDate.getDay() !== 1) return false;

            const dayOfMonth = checkDate.getDate();
            const isSecondMonday = (dayOfMonth > 7 && dayOfMonth <= 14);
            const isFourthMonday = (dayOfMonth > 21 && dayOfMonth <= 28);

            return isSecondMonday || isFourthMonday;
        },
        // Checks only if date is in the past
        isPastBasic(date) {
            const checkDate = new Date(this.currentYear, this.currentMonth, date);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            return checkDate < today;
        },
        // Combined logic for the UI 'disabled' class
        isPast(date) {
            return this.isPastBasic(date) || !this.isAllowedDate(date);
        },
        toggleDate(date) {
            if (this.isDateFull(date) && !this.isPast(date) && this.isAllowedDate(date)) {
                Swal.fire('Slot Full', 'The daily maximum number of appointments (3/3) has been reached for this date.', 'error');
                return;
            }
            if (this.isPast(date)) return;
            const targetDate = new Date(this.currentYear, this.currentMonth, date);
            if (this.isSelected(date)) {
                this.selectedDate = null;
            } else {
                this.selectedDate = targetDate;
            }
        },
        isSelected(date) {
            if (!this.selectedDate) return false;
            return this.selectedDate.getDate() === date &&
                this.selectedDate.getMonth() === this.currentMonth &&
                this.selectedDate.getFullYear() === this.currentYear;
        },
        isToday(date) {
            const today = new Date();
            return today.getDate() === date &&
                today.getMonth() === this.currentMonth &&
                today.getFullYear() === this.currentYear;
        },
        formatLocalDate(dateObj) {
            const year = dateObj.getFullYear();
            const month = String(dateObj.getMonth() + 1).padStart(2, '0');
            const day = String(dateObj.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        },
        proceedToStep2() {
            this.$router.push({
                name: 'CivilWeddingAppointmentForm',
                query: { date: this.formatLocalDate(this.selectedDate) }
            });
        }
    }
};
</script>

<style scoped>
.main-container {
    font-family: 'Inter', sans-serif;
    background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)),
        url('../../../../../public/background.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}

.glass-card {
    background: rgba(255, 255, 255, 0.94) !important;
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 1.5rem;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
}

.calendar-day {
    aspect-ratio: 1 / 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(0, 0, 0, 0.05);
    cursor: pointer;
    position: relative;
    transition: all 0.2s ease-in-out;
}

/* Specific styling for allowed Mondays to guide the user */
.available-monday:not(.selected) {
    background: rgb(48, 155, 27);
    border: 1px dashed rgba(13, 110, 253, 0.3);
}

.calendar-day:hover:not(.disabled):not(.full-day) {
    background: rgb(48, 155, 27);
    transform: scale(1.05);
    z-index: 5;
}

.day-number {
    font-size: 1.1rem;
    font-weight: 700;
    color: #212529;
}

.calendar-day.today {
    box-shadow: inset 0 0 0 2px #0d6efd;
}

.calendar-day.selected {
    background: #0d6efd !important;
    box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4);
}

.calendar-day.selected .day-number {
    color: white;
}

.calendar-day.disabled {
    background: #f8f9fa;
    cursor: not-allowed;
    opacity: 0.3;
}

.calendar-day.disabled .day-number {
    font-weight: 400;
}

.calendar-day.full-day {
    background: #f8d7da !important;
    border: 1px solid #dc3545;
    cursor: not-allowed;
    opacity: 1;
}

.calendar-day.full-day .day-number {
    color: #842029;
}

.selection-mark {
    color: #ff3b30;
    font-size: 0.8rem;
    position: absolute;
    top: 4px;
    right: 4px;
}

.slot-indicator {
    position: absolute;
    top: 4px;
    left: 4px;
    font-size: 0.7rem;
    font-weight: 700;
    color: #0d6efd;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 10px;
    padding: 1px 6px;
    line-height: 1.2;
}

.slot-indicator.full {
    color: #dc3545;
}

.letter-spacing-2 {
    letter-spacing: 2px;
}

@media (max-width: 576px) {
    .calendar-day {
        aspect-ratio: 1 / 1.2;
    }

    .day-number {
        font-size: 0.9rem;
    }

    .container {
        padding-left: 10px;
        padding-right: 10px;
    }
}

.animate-pop {
    animation: pop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

@keyframes pop {
    0% {
        transform: scale(0);
        opacity: 0;
    }

    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
