class BookingCalendar {
    constructor() {
        this.currentDate = new Date();
        this.selectedDate = null;
        this.selectedTime = null;
        this.availableDates = this.generateAvailableDates();
        this.timeSlots = [
            '09:00', '10:00', '11:00', '14:00', '15:00', '16:00', '17:00'
        ];
        
        this.init();
    }

    init() {
        this.renderCalendar();
        this.bindEvents();
    }

    generateAvailableDates() {
        const dates = [];
        const today = new Date();
        
        for (let i = 1; i <= 30; i++) {
            const date = new Date(today);
            date.setDate(today.getDate() + i);
            if (date.getDay() !== 0) {
                dates.push(date.toDateString());
            }
        }
        return dates;
    }

    renderCalendar() {
        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();
        
        const monthNames = ['1月', '2月', '3月', '4月', '5月', '6月', 
                          '7月', '8月', '9月', '10月', '11月', '12月'];
        document.getElementById('calendarTitle').textContent = `${year}年${monthNames[month]}`;
        
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeek = firstDay.getDay();
        
        const calendarGrid = document.getElementById('calendarGrid');
        calendarGrid.innerHTML = '';
        
        const dayHeaders = ['日', '一', '二', '三', '四', '五', '六'];
        dayHeaders.forEach(day => {
            const dayHeader = document.createElement('div');
            dayHeader.className = 'calendar-day';
            dayHeader.style.fontWeight = '600';
            dayHeader.style.color = '#666';
            dayHeader.textContent = day;
            calendarGrid.appendChild(dayHeader);
        });
        
        for (let i = 0; i < startingDayOfWeek; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'calendar-day disabled';
            calendarGrid.appendChild(emptyDay);
        }
        
        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = document.createElement('div');
            const currentDateStr = new Date(year, month, day).toDateString();
            
            dayElement.className = 'calendar-day';
            dayElement.textContent = day;
            
            if (this.availableDates.includes(currentDateStr)) {
                dayElement.classList.add('available');
                dayElement.addEventListener('click', () => this.selectDate(currentDateStr, dayElement));
            } else {
                dayElement.classList.add('disabled');
            }
            
            calendarGrid.appendChild(dayElement);
        }
    }

    selectDate(dateStr, element) {
        document.querySelectorAll('.calendar-day.selected').forEach(el => el.classList.remove('selected'));
        element.classList.add('selected');
        this.selectedDate = dateStr;
        document.getElementById('selectedDate').value = dateStr;
        this.renderTimeSlots();
        document.getElementById('timeSlotsContainer').style.display = 'block';
    }

    renderTimeSlots() {
        const timeSlotsContainer = document.getElementById('timeSlots');
        timeSlotsContainer.innerHTML = '';
        
        this.timeSlots.forEach(time => {
            const timeSlot = document.createElement('div');
            timeSlot.className = 'time-slot';
            timeSlot.textContent = time;
            timeSlot.addEventListener('click', () => this.selectTime(time, timeSlot));
            timeSlotsContainer.appendChild(timeSlot);
        });
    }

    selectTime(time, element) {
        document.querySelectorAll('.time-slot.selected').forEach(el => el.classList.remove('selected'));
        element.classList.add('selected');
        this.selectedTime = time;
        document.getElementById('selectedTime').value = time;
    }

    bindEvents() {
        document.getElementById('prevMonth').addEventListener('click', () => {
            this.currentDate.setMonth(this.currentDate.getMonth() - 1);
            this.renderCalendar();
        });

        document.getElementById('nextMonth').addEventListener('click', () => {
            this.currentDate.setMonth(this.currentDate.getMonth() + 1);
            this.renderCalendar();
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const calendar = new BookingCalendar();
    const form = document.getElementById('bookingForm');
    const submitBtn = document.getElementById('submitBtn');
    const successMessage = document.getElementById('successMessage');
    const errorMessage = document.getElementById('errorMessage');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        errorMessage.style.display = 'none';
        
        const formData = new FormData(form);
        const requiredFields = ['parentName', 'phone', 'childName', 'childAge', 'courseType'];
        
        for (let field of requiredFields) {
            if (!formData.get(field)) {
                showError('请填写所有必填项');
                return;
            }
        }
        
        const phone = formData.get('phone');
        if (!/^1[3-9]\d{9}$/.test(phone)) {
            showError('请输入正确的手机号码');
            return;
        }
        
        if (!calendar.selectedDate || !calendar.selectedTime) {
            showError('请选择试听日期和时间');
            return;
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> 提交预约中...';
        
        setTimeout(() => {
            successMessage.style.display = 'block';
            form.style.display = 'none';
            
            setTimeout(() => {
                form.reset();
                form.style.display = 'block';
                successMessage.style.display = 'none';
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-calendar-check"></i> 确认预约试听';
                
                calendar.selectedDate = null;
                calendar.selectedTime = null;
                document.getElementById('timeSlotsContainer').style.display = 'none';
                document.querySelectorAll('.calendar-day.selected, .time-slot.selected').forEach(el => {
                    el.classList.remove('selected');
                });
            }, 5000);
        }, 2000);
    });

    function showError(message) {
        const errorMessage = document.getElementById('errorMessage');
        errorMessage.textContent = message;
        errorMessage.style.display = 'block';
        errorMessage.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
});