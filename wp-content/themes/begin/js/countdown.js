jQuery(document).ready(function($) {
	function life_time() {
		function getAsideLifeTime() {
			let nowDate = +new Date();
			let todayStartDate = new Date(new Date().toLocaleDateString()).getTime();
			let todayPassHours = (nowDate - todayStartDate) / 1000 / 60 / 60;
			let todayPassHoursPercent = (todayPassHours / 24) * 100;
			if ( todayPassHoursPercent < 30 ) {
				$('.countdown-day .besea').addClass('prime');
			} else {
				$('.countdown-day .besea').removeClass('prime');
			}
			$('#dayprogress .countdown-time span').html(parseInt(todayPassHours));
			$('#dayprogress .bewave').css('bottom', parseInt(todayPassHoursPercent) + '%');
			$('#dayprogress .progress-count').html(parseInt(todayPassHoursPercent) + '%');
			let weeks = {
				0 : 7,
				1 : 1,
				2 : 2,
				3 : 3,
				4 : 4,
				5 : 5,
				6 : 6
			};
			let weekDay = weeks[new Date().getDay()];
			let weekDayPassPercent = (weekDay / 7) * 100;
			if ( weekDayPassPercent < 30 ) {
				$('.countdown-week .besea').addClass('prime');
			} else {
				$('.countdown-week .besea').removeClass('prime');
			}
			$('#weekprogress .countdown-time span').html(weekDay);
			$('#weekprogress .bewave').css('bottom', parseInt(weekDayPassPercent) + '%');
			$('#weekprogress .progress-count').html(parseInt(weekDayPassPercent) + '%');
			let year = new Date().getFullYear();
			let date = new Date().getDate();
			let month = new Date().getMonth() + 1;
			let monthAll = new Date(year, month, 0).getDate();
			let monthPassPercent = (date / monthAll) * 100;
			if ( monthPassPercent < 30 ) {
				$('.countdown-month .besea').addClass('prime');
			} else {
				$('.countdown-month .besea').removeClass('prime');
			}
			$('#monthprogress .countdown-time span').html(date);
			$('#monthprogress .bewave').css('bottom', parseInt(monthPassPercent) + '%');
			$('#monthprogress .progress-count').html(parseInt(monthPassPercent) + '%');
			let yearPass = (month / 12) * 100;
			if ( yearPass < 30 ) {
				$('.countdown-year .besea').addClass('prime');
			} else {
				$('.countdown-year .besea').removeClass('prime');
			}
			$('#yearprogress .countdown-time span').html(month);
			$('#yearprogress .bewave').css('bottom', parseInt(yearPass) + '%');
			$('#yearprogress .progress-count').html(parseInt(yearPass) + '%');
		}
		getAsideLifeTime();
		setInterval(() => {
			getAsideLifeTime();
		}, 1000);
	}
	life_time()
})