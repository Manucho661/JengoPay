<script type="text/javascript">
	$(document).ready(function(){

		if($("#residentialPropertyType").click(function(){
			$("#propertyorHousingTypesLists").show();
			$("#propertCategoryListings").show();
		})){

		}else {
			$("#propertyorHousingTypesLists").hide();
			$("#propertCategoryListings").hide();
		}
		$("#backToSingleDescriptionListings").click(function(){
			alert('Back to Listings')
		})

		$("#nextStepToLocation").click(function(e){
			e.preventDefault();
			alert('Open Location');
		});

		$("#backToLocationDetails").click(function(){
			alert('Go Back to Location');
		});

		$("#nextToUnitsDetails").click(function(e){
			e.preventDefault();
			alert('Next to Units');
		});

		$("#backTosinglesUnitsDetails").click(function(){
			alert('Back to Location');
		});

		$("#nextTosinglesPropertyDescription").click(function(e){
			e.preventDefault();
			alert('Next to Description');
		});

		$("#backTosinglesPropertyDescription").click(function(e){
			alert('Back to Description');
		});

		$("#nextTosinglesRentalAgreement").click(function(e){
			e.preventDefault();
			alert('Next to Rental Agreement');
		});

		$("#backTosinglesRentalAgreement").click(function(){
			alert('Back to Description');
		});

		$("#nextTosinglePropertyPhotos").click(function(e){
			e.preventDefault();
			alert('Next to Photos');
		});

		$("#backsinglesRentalAgreement").click(function(){
			alert('Back to Description');
		});

		$("#nextToFinalStep").click(function(e){
			e.preventDefault();
			alert('Next to SUbmit');
		});

		$("#backtosinglePropertyPhotos").click(function(){
			alert('Back to Photos');
		})

	});
</script>