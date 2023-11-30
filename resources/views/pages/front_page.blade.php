<x-main-layout>
	<x-slot name="title">Useful calendar</x-slot>
	<x-slot name="description">Here will be a description of the best calendar.</x-slot>
	<x-slot name="center">
		<main>
		@php
        function printMonth($month_shift) {
            $html_days = '';
            if($month_shift == 0) $currentDate = now();
            else $currentDate = now()->addMonthNoOverflow($month_shift)->day(15);
            $firstDayOfMonth = $currentDate->copy()->startOfMonth();
            $lastDayOfMonth = $currentDate->copy()->endOfMonth();
            $firstDayOfWeek = $firstDayOfMonth->dayOfWeek;
            $numDaysInMonth = $currentDate->copy()->daysInMonth;
            $other_month_day = 0;
            $day = 0;
            $print_current_month = false;
            $monthNumber = $currentDate->copy()->format('m');
            $yearNumber = $currentDate->copy()->format('Y');
            $idOfElement = $day.'-'.$currentDate->copy()->format('m').'-'.$currentDate->copy()->format('Y');

            if($firstDayOfWeek !==1) {
                $lastMonth = $firstDayOfMonth->subMonth();
                $lastSundayOfLastMonth = $lastMonth->endOfMonth()->previous('Sunday');
                $other_month_day = $lastSundayOfLastMonth->day - 1;
            }
            else $print_current_month = true;
            for ($i = 1; $i <= 42; $i++) {
                if($i == $firstDayOfWeek + 1) { $print_current_month = true; $other_month_day = 0;}
                if($day == $lastDayOfMonth->day) { 
                    $print_current_month = false;
                }
                if($print_current_month) { 
                    $day++;
                    $id_day = $day < 10 ? '0'.$day : $day;
                    $idOfElement = $id_day.'-'.$currentDate->copy()->format('m').'-'.$currentDate->copy()->format('Y');
                    $html_days .= '<div class="day" id="'.$idOfElement.'"><p class="current_month">'.$day.'</p></div>';
                }
                else {
                    $other_month_day++;
                    $html_days .= '<div class="day"><p>'.$other_month_day.'</p></div>';
                }
            }
            return $html_days;
        }
        function printTimeOptions() {
            $options = '';
            for ($i = 0; $i < 24; $i++) {
                $hour = $i < 10 ? '0'.$i : $i;
                $options .= '<option>'.$hour.':00</option>';
            }
            return $options;
        }
        @endphp
			<h1>Calendar</h1>
			<div id="filter_box">
				<div class='filter_item' onclick="filter(event,'1')">Meeting with an Expert</div>
				<div class='filter_item' onclick="filter(event,'2')">Question-Answer</div>
				<div class='filter_item' onclick="filter(event,'3')">Conference</div>
				<div class='filter_item' onclick="filter(event,'4')">Webinare</div>
			</div>
			@for ($i = 0; $i < 2; $i++)
			<div class="quarter_box">
				@php
					if($i == 0) {$j = 0; $end = 3;}
					else { $j = 3; $end = 6;}
				@endphp
				@for(; $j < $end; $j++)  
				<div class="month_wrap">
					<div class="month_box">
						<p class="month_title">{{ $j==0 ? now()->englishMonth : now()->addMonth($j)->englishMonth }}</p>
						<div class="week_days">
							<p>Sun</p><p>Mon</p><p>Tue</p><p>Wed</p><p>Thu</p><p>Fri</p><p>Sat</p>
						</div>
						<div class="days_box">
							{!! printMonth($j) !!}
						</div>
					</div>
				</div>
				@endfor
			</div>
			@endfor
		</main>
		<div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p id="modal_title">Add event</p>
                <div class="form_conteiner">
                    <input type="text" value id="new_event_name" placeholder="Event name...">
                    <textarea id="new_event_description" placeholder="Event description..."></textarea>
                    <input type="text" id="new_event_location" placeholder="Location...">
                    <p class="date_time"><span id="modal_month">10 july at </span><select id="new_event_time">{{!! printTimeOptions() !!}}</select></p>
                    <select id="new_event_type"><option value='1'>Meeting with an Expert</option><option value='2'>Question-Answer</option><option value='3'>Conference</option><option value='4'>Webinare</option></select>
                    <input type="hidden" value='' id="new_event_date">
                    <input type="hidden" value='' id="event_id">
                    <p id="validation_err"></p>
                    <div id="button_wrap"><button id="cansel_button">Cansel</button><button id="add_button">Add</button></div>
                </div>
            </div>
        </div>
        <div id="info_popup" class="modal-content info">
            <p>Events</p>
            <div id="events_wrap" class="scrollable-container custom-scrollbar">
            </div>
            <p id="info_button_wrap"><button id="add_event">Add event</button></p>
        </div>
	</x-slot>
</x-main-layout>
