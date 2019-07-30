<template>
    <div :class="wrapperClass" class="YggDate__datepicker">
        <input
            :class="inputClass"
            :clear-button="clearButton"
            :disabled="disabledPicker"
            :id="id"
            :name="name"
            :placeholder="placeholder"
            :required="required"
            :type="inline ? 'hidden' : 'text'"
            :value="formattedValue"
            @click="showCalendar()"
            readonly>
        <i @click="clearDate()" class="vdp-datepicker__clear-button" v-if="clearButton && selectedDate">&times;</i>
        <!-- Day View -->
        <div :class="{open:showDayView}" class="YggDate__calendar" v-bind:style="calendarStyle" v-show="showDayView">
            <header>
                <span
                    @click="previousMonth"
                    class="prev"
                    v-bind:class="{ 'disabled' : previousMonthDisabled(pageTimestamp) }">
                    <svg fill-rule="evenodd" height="12" viewBox="0 0 8 12" width="8">
                        <path d="M7.5 10.6L2.8 6l4.7-4.6L6.1 0 0 6l6.1 6z"></path>
                    </svg>
                </span>
                <span @click="showMonthCalendar" class="up">
                    <span class="YggDate__cur-month">{{ currMonthName }}</span>
                    <span class="YggDate__cur-year">{{ currYear }}</span>
                </span>
                <span
                    @click="nextMonth"
                    class="next"
                    v-bind:class="{ 'disabled' : nextMonthDisabled(pageTimestamp) }">
                    <svg fill-rule="evenodd" height="12" viewBox="0 0 8 12" width="8">
                        <path d="M0 10.6L4.7 6 0 1.4 1.4 0l6.1 6-6.1 6z"></path>
                    </svg>
                </span>
            </header>
            <div class="YggDate__innerContainer">
                <div class="YggDate__rContainer">
                    <div class="YggDate__weekdays">
                        <span class="cell day-header" v-for="d in daysOfWeek">{{ d }}</span>
                    </div>
                    <div class="YggDate__days">
                        <div class="YggDate__dayContainer">
                            <span class="cell day blank" v-for="d in blankDays"></span>
                            <span @click="selectDate(day)"
                                  class="cell day"
                                  v-bind:class="dayClasses(day)"
                                  v-for="day in days">{{ day.date }}
                            </span>
                            <span class="cell day blank" v-for="d in remainingDays"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Month View -->
        <div :class="{open:showMonthView}" class="YggDate__calendar" v-bind:style="calendarStyle"
             v-show="showMonthView">
            <header>

                <span
                    @click="previousYear"
                    class="prev"
                    v-bind:class="{ 'disabled' : previousYearDisabled(pageTimestamp) }">
                    <svg fill-rule="evenodd" height="12" viewBox="0 0 8 12" width="8">
                        <path d="M7.5 10.6L2.8 6l4.7-4.6L6.1 0 0 6l6.1 6z"></path>
                    </svg>
                </span>
                <span @click="showYearCalendar" class="up">
                    <span class="YggDate__cur-year">{{ getPageYear() }}</span>
                </span>
                <span
                    @click="nextYear"
                    class="next"
                    v-bind:class="{ 'disabled' : nextYearDisabled(pageTimestamp) }">
                    <svg fill-rule="evenodd" height="12" viewBox="0 0 8 12" width="8">
                        <path d="M0 10.6L4.7 6 0 1.4 1.4 0l6.1 6-6.1 6z"></path>
                    </svg>
                </span>
            </header>
            <div class="YggDate__innerContainer">
                <div class="YggDate__rContainer">
                    <div class="YggDate__monthContainer">
                        <span @click.stop="selectMonth(month)"
                              class="cell month"
                              v-bind:class="{ 'selected': month.isSelected, 'disabled': month.isDisabled }"
                              v-for="month in months">{{ month.month }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Year View -->
        <div :class="{open:showYearView}" class="YggDate__calendar" v-bind:style="calendarStyle" v-show="showYearView">
            <header>
                <span @click="previousDecade" class="prev"
                      v-bind:class="{ 'disabled' : previousDecadeDisabled(pageTimestamp) }">
                    <svg fill-rule="evenodd" height="12" viewBox="0 0 8 12" width="8">
                        <path d="M7.5 10.6L2.8 6l4.7-4.6L6.1 0 0 6l6.1 6z"></path>
                    </svg>
                </span>
                <span class="up">
                    <span class="YggDate__cur-decade">{{ getPageDecade() }}</span>
                </span>
                <span @click="nextDecade" class="next"
                      v-bind:class="{ 'disabled' : nextMonthDisabled(pageTimestamp) }">
                    <svg fill-rule="evenodd" height="12" viewBox="0 0 8 12" width="8">
                        <path d="M0 10.6L4.7 6 0 1.4 1.4 0l6.1 6-6.1 6z"></path>
                    </svg>
                </span>
            </header>
            <div class="YggDate__innerContainer">
                <div class="YggDate__rContainer">
                    <div class="YggDate__yearContainer">
                    <span
                        @click.stop="selectYear(year)"
                        class="cell year"
                        v-bind:class="{ 'selected': year.isSelected, 'disabled': year.isDisabled }"
                        v-for="year in years">{{ year.year }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
    import DatePicker from 'vuejs-datepicker';

    export default {
        name: 'YggDatepicker',
        extends: DatePicker,

        computed: {
            remainingDays() {
                let diff = this.days.length + this.blankDays;
                let rem = diff > 35 ? 42 - diff : 35 - diff;
                return rem;
            },
        },

        methods: {
            init() {
                if (this.value) {
                    this.setValue(this.value)
                }
                this.showDayCalendar();
            },
            clickOutside() {

            }
        },
    }
</script>
