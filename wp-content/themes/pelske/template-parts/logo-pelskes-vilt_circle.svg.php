<?php
	// Make the gradient unique so we can use this svg multiple times on the same page
	$unique_id = rand(1,9999);
?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 406 406" width="203" height="203" class="logo-svg" opacity="0">
  <defs>
		<radialGradient id="Gradient-<?php echo $unique_id; ?>"	cx="50%" cy="50%" r="50%" fx="50%" fy="50%" gradientUnits="userSpaceOnUse">
			<stop offset="0%" stop-color="#D700B9"/>
			<stop offset="100%" stop-color="#D076FF"/>
		</radialGradient>
  </defs>
	<g class="border">
		<circle class="el" id="circle" fill="none" stroke="#000000" stroke-width="7" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" cx="203.5" cy="203.5" r="198" transform="rotate(-180 203.5 203.5)" />
	</g>
	<g class="writing">
		<path class="el" id="P" fill="none" stroke="url(#Gradient-<?php echo $unique_id; ?>)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M89.5,322.5c15-162-1.6-240,2-244C161,0,245,176,95.5,149.5"/>
		<path class="el" id="e" fill="none" stroke="url(#Gradient-<?php echo $unique_id; ?>)" stroke-width="6" stroke-linecap="round" stroke-linejoin="round" d="M160.5,142.5c9-58-43.9-47-43,7C119,242,168,245,185,192"/>
		<path class="el" id="l" fill="none" stroke="url(#Gradient-<?php echo $unique_id; ?>)" stroke-width="6" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M188.5,135.5c101-93,10.7-128.6,5-74c-6,58-12.4,190-18,253c-6,68,43,80,59,2"/>
		<path class="el" id="s" fill="none" stroke="url(#Gradient-<?php echo $unique_id; ?>)" stroke-width="6" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M246.5,124.5c-20-50-57.3,36.3-12,18c28.5-11.5,36,92-17,90c-30-1.1-33-45-33-45"/>
		<g id="k">
			<path class="el" id="leg_left" fill="none" stroke="url(#Gradient-<?php echo $unique_id; ?>)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M264.5,47.5c-26,6,2,73-5,186"/>
			<path class="el" id="leg_right" fill="none" stroke="url(#Gradient-<?php echo $unique_id; ?>)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M333.5,199.5c-4,34.8-37.9,24.5-43,2c-7.6-33.6,8-82-29.9-52"/>
			<path class="el" id="top_curl" fill="none" stroke="url(#Gradient-<?php echo $unique_id; ?>)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M261.8,147c39.7-24.5,31.7-70.5-4.3-34.5"/>
		</g>
		<path class="el" id="e_last" fill="none" stroke="url(#Gradient-<?php echo $unique_id; ?>)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M289.3,191.8c23.3,0.1,41.1-6,41.1-6c-9.3-46.1-51.6-36.1-38.7,19.8"/>
		<path class="el" id="s_last" fill="none" stroke="url(#Gradient-<?php echo $unique_id; ?>)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M340.5,116.5c-20-50-59.5,35.7-14,18c42.5-16.5,53.5,129.3,8,130c-30.5,0.5-30-43-30-43"/>
		<path class="el" id="V" fill="none" stroke="url(#Gradient-<?php echo $unique_id; ?>)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M40.5,175.5c20.4,40.8,36.1,89.4,49,147"/>
		<g id="i">
			<path class="el" id="i_leg" fill="none" stroke="url(#Gradient-<?php echo $unique_id; ?>)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M158.5,313.5c-9,56-41,53-39,7c0.9-21,2-37,1-51"/>
			<circle class="el shape" id="i_dot" fill="url(#Gradient-<?php echo $unique_id; ?>)" stroke="#D076FF" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" cx="118.5" cy="253.5" r="6"/>
		</g>
		<g id="t">
			<path class="el" id="t_leg" fill="none" stroke="url(#Gradient-<?php echo $unique_id; ?>)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M260,230c-17,195,55.5,107.5,55.5,71.5"/>
			<path class="el" id="cross" fill="none" stroke="url(#Gradient-<?php echo $unique_id; ?>)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M235.5,256.5c17,0,50,0.9,70,2"/>
		</g>
	</g>
</svg>
