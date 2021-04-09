<div class="col-md-12 col-sm-12 filter-section">
				
	<div class="col-md-6 col-sm-6">
		<div class="col-md-8 col-sm-8">
		<i class="fa fa-search" aria-hidden="true"></i>
		<input id="myInput" class="search_input" type="text" value="<?php echo $_SESSION['current_pincode']; ?>" placeholder="Enter Pincode or Location" autocomplete="new-password"/>

		<table id="address" style="display: none">
		     <tr>
		     	<td class="slimField"><input class="field" id="street_number"
		             disabled="true"></td>
		       	<td class="wideField" colspan="2"><input class="field" id="route"
		             disabled="true"></td>
		     </tr>
		     <tr>		       
		       	<td class="wideField" colspan="3"><input class="field" id="locality"
		             disabled="true"></td>
		     </tr>
		     <tr>
		       <td class="slimField"><input class="field"
		             id="administrative_area_level_1" disabled="true"></td>
		       <td class="wideField"><input class="field" id="postal_code"
		             disabled="true" value="<?php echo $_SESSION['current_pincode']; ?>"></td>
		     </tr>
		     <tr>
		       <td class="label">Country</td>
		       <td class="wideField" colspan="3"><input class="field"
		             id="country" disabled="true"></td>
		     </tr>
	   	</table>
	   </div>
	   <div class="col-md-4 col-sm-4">

	 
			<button class="button searchbtn" >Change</button>
			<div class="pincode-error" style="color:red"></div>
		</div>

	</div>

	<div class="col-md-6 col-sm-6">
		<div style="text-align: right;">
			<button class="button show-map">Show Map</button>
		</div>
	</div>
</div>
		