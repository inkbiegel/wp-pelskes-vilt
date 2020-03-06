(function( $ ) {
	'use strict';

	$(document).ready(function() {

		if( $('#siteLogo').length > 0 ) {
			var siteLogo = new Vivus('siteLogo', {
												type: 'scenario',
												file: '/wp-pelskes-vilt/whitesheepweresheared/wp-content/themes/pelske/img/logo-pelskes-vilt_vines.svg',
												animTimingFunction: Vivus.EASE,
												duration: 200,
												onReady: function(siteLogo) {
													siteLogo.parentEl.classList.add('finished');
												}
											});
		}

		if( $('#vinesTest').length > 0 ) {
			// initAnimatedVines();
		}

		function initAnimatedVines(){
			var canvas = document.getElementById('vinesTest');
			var context = canvas.getContext('2d');
			// Create branch array and add first branch
			var branches = [];
			branches.push({
				points: [{x: 100, y: 100}, {x: 100, y: 100}, {x: 100, y: 100}, {x: 100, y: 100}],
				direction: 0
			});

			// Kick things off
			animatedVines(context, branches, 0);
		}

		function animatedVines(context, branches, t) {

			// Draw b-spline segment for each branch
			for (var i in branches) {

				animatedBSpline(context, branches[i].points, t);

				// Keep going until t = 1
				if (t < 1) requestAnimationFrame(function() {
					animatedBSpline(context, branches[i].points, t + 0.1);
				});

			}

			// If finished drawing branch
			if (t >= 1) {

				// Create new branch array
				var new_branches = [];
				for (var j in branches) {

					// Replace each existing branch with two branches
					for (var k = 0; k < 2; k++) {

						// Generate random length and direction
						var direction = branches[j].direction - (Math.random() * 180 - 90);
						var length = Math.random() * 20 + 5;

						// Calculate new point
						var new_point = {
							x: branches[j].points[3].x + Math.sin(Math.PI * direction / 180) * length,
							y: branches[j].points[3].y - Math.cos(Math.PI * direction / 180) * length,
						}

						// Add to new branch array
						new_branches.push({
							points: [
								branches[j].points[1],
								branches[j].points[2],
								branches[j].points[3],
								new_point
							],
							direction: direction
						});
					}
				}
				while (new_branches.length > 10) {
					new_branches.splice(Math.floor(Math.random() * new_branches.length), 1);
				}

				// Start things off with the new set
				requestAnimationFrame(function() {
					animatedVines(context, new_branches, 0);
				});

				// Not finished drawing the old set
			} else {
				requestAnimationFrame(function() {
					animatedVines(context, branches, t + 0.1);
				});
			}

			function animatedBSpline(context, points, t) {
				// Draw curve segment
				var ax = (-points[0].x + 3 * points[1].x - 3 * points[2].x + points[3].x) / 6;
				var ay = (-points[0].y + 3 * points[1].y - 3 * points[2].y + points[3].y) / 6;
				var bx = (points[0].x - 2 * points[1].x + points[2].x) / 2;
				var by = (points[0].y - 2 * points[1].y + points[2].y) / 2;
				var cx = (-points[0].x + points[2].x) / 2;
				var cy = (-points[0].y + points[2].y) / 2;
				var dx = (points[0].x + 4 * points[1].x + points[2].x) / 6;
				var dy = (points[0].y + 4 * points[1].y + points[2].y) / 6;
				context.beginPath();
				context.moveTo(
					ax * Math.pow(t, 3) + bx * Math.pow(t, 2) + cx * t + dx,
					ay * Math.pow(t, 3) + by * Math.pow(t, 2) + cy * t + dy
				);
				context.lineTo(
					ax * Math.pow(t + 0.1, 3) + bx * Math.pow(t + 0.1, 2) + cx * (t + 0.1) + dx,
					ay * Math.pow(t + 0.1, 3) + by * Math.pow(t + 0.1, 2) + cy * (t + 0.1) + dy
				);
				context.stroke();
			}

	}

});

})( jQuery );