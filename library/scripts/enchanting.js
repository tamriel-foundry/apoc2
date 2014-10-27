//description prefix
var add = "Adds ";
var deal = "Deals ";
var reduce = "Reduce ";
var increase = "Increase ";

//checkbox quick selection
var pot = "input.potency-rune";
var ess = "input.essence-rune";
var asp = "input.aspect-rune";

//effect value
var x = $('.effect-value');

//array index numbers
var table = 0;
var quality = 0;
var level = 0;

//Array 0:default 1-14:see dulfy table numbering 15:extra
var MyArray = [];
MyArray[0] = [[1,2,3,4,5,6,7,8,9,10,11,12,13,14],[2,3,4,5,6,7,8,9,10,11,12,13,14,15],[3,4,5,6,7,8,9,10,11,12,13,14,15,16],[4,5,6,7,8,9,10,11,12,13,14,15,16,17],[5,6,7,8,9,10,11,12,13,14,15,16,17,18]];
MyArray[1] = [[7,12,17,29,35,41,47,52,64,64,64,66,70,74],[9,14,19,31,37,43,49,54,66,66,66,68,72,76],[12,17,22,34,40,44,52,57,69,69,69,71,75,79],[15,20,25,37,43,47,55,60,72,72,72,74,78,82],[18,23,28,40,46,50,58,63,75,75,75,77,81,85]];
MyArray[2] = [[10,18,25,43,52,61,70,78,96,96,96,99,105,111],[13,21,28,46,55,64,73,81,99,99,99,102,108,114],[18,25,33,51,60,66,78,85,103,103,103,106,112,118],[22,30,37,55,64,70,82,90,108,108,108,111,117,123],[27,34,42,60,69,75,87,94,112,112,112,115,121,127]];
MyArray[3] = [[3,4,7,10,12,15,18,21,27,27,30,31,32,34],[4,6,9,12,14,17,20,23,29,29,32,33,34,36],[5,8,11,14,16,19,22,25,31,31,34,35,36,38],[9,12,15,18,20,23,26,29,35,35,38,39,40,42],[14,17,20,23,25,28,31,34,40,40,43,44,45,47]];
MyArray[4] = [[3,4,5,6,7,8,9,10,12,12,12,13,14,14],[4,5,6,7,8,9,10,11,13,13,13,13,14,15],[4,5,6,7,8,9,10,11,13,13,13,14,15,16],[5,6,7,8,9,10,11,12,14,14,14,14,15,16],[6,7,8,9,10,11,12,13,15,15,15,15,16,17]];
MyArray[5] = [[6,9,13,16,19,23,26,29,36,36,110,114,120,124],[9,16,23,29,36,42,49,56,69,69,210,218,230,238],[13,23,33,42,52,62,72,82,102,102,310,322,340,352],[16,29,42,56,69,82,85,108,135,135,410,426,450,466],[19,36,52,69,85,102,118,135,168,168,510,530,560,580]];
MyArray[6] = [[2,3,5,7,9,11,13,15,20,20,22,23,24,25],[3,4,6,9,10,12,15,17,21,21,24,25,25,27],[3,6,8,10,12,14,16,18,23,23,25,27,27,28],[6,9,11,13,15,17,19,21,26,26,28,30,30,31],[10,12,15,17,18,21,23,25,30,30,32,33,33,35]];
MyArray[7] = [[1,1,2,2,3,3,4,4,5,5,6,6,7,8],[2,2,3,3,4,4,5,5,6,6,7,7,8,9],[3,3,4,4,5,5,6,6,7,7,8,8,9,10],[4,4,5,5,6,6,7,7,8,8,9,9,10,11],[5,5,6,6,7,7,8,8,9,9,10,10,11,12]];
MyArray[8] = [[1,2,3,4,5,6,7,8,10,10,10,11,12,13],[2,3,4,5,6,7,8,9,11,11,11,12,13,14],[3,4,5,6,7,8,9,10,12,12,12,13,14,15],[4,5,6,7,8,9,10,11,13,13,13,14,15,16],[5,6,7,8,9,10,11,12,14,14,14,15,16,17]];
MyArray[9] = [[2,5,7,10,12,15,17,20,25,25,25,26,27,28],[3,5,8,10,13,15,18,20,25,25,25,26,28,29],[3,6,8,11,13,16,18,21,26,26,26,27,28,29],[4,6,9,11,14,16,19,21,26,26,26,27,29,30],[4,7,9,12,14,17,19,22,27,27,27,28,29,30]];
MyArray[10] = [[5,10,15,20,25,30,35,40,50,50,50,52,55,57],[6,11,16,21,26,31,36,41,51,51,51,53,56,58],[7,12,17,22,27,32,37,42,52,52,52,54,57,59],[8,13,18,23,28,33,38,443,53,53,53,55,58,60],[9,14,19,24,29,34,39,44,54,54,54,56,59,61]];
MyArray[11] = [[25,50,75,100,125,150,175,200,250,250,250,260,275,285],[50,100,150,200,250,300,350,400,500,500,500,520,550,570],[75,150,225,300,375,450,525,600,750,750,750,780,825,855],[100,200,300,400,500,600,700,800,1000,1000,1000,1040,1100,1140],[125,250,375,500,625,750,875,1000,1250,1250,1250,1300,1375,1425]];
MyArray[12] = [[3,5,6,7,8,9,10,12,15,15,15,16,17,18],[4,6,7,8,9,10,11,13,16,16,16,17,18,19],[5,7,8,9,10,11,12,14,17,17,17,18,19,20],[6,8,9,10,11,12,,13,15,18,18,18,19,20,21],[7,9,10,11,12,13,14,16,19,19,19,20,21,22]];
MyArray[12] = [[3,5,6,7,8,9,10,12,15,15,15,16,17,18],[4,6,7,8,9,10,11,13,16,16,16,17,18,19],[5,7,8,9,10,11,12,14,17,17,17,18,19,20],[6,8,9,10,11,12,,13,15,18,18,18,19,20,21],[7,9,10,11,12,13,14,16,19,19,19,20,21,22]];
MyArray[13] = [[4,7,9,10,12,13,15,18,22,22,22,24,25,27],[6,9,10,12,13,15,16,19,24,24,24,25,27,28],[7,10,12,13,15,16,18,21,25,25,25,27,28,30],[9,12,13,15,16,18,19,22,27,27,27,28,30,31],[10,13,15,16,18,19,21,24,28,28,28,30,31,33]];
MyArray[14] = [[1,2,2,3,4,5,6,7,9,9,10,10,10,11],[1,3,4,4,4,5,6,7,9,9,10,11,11,12],[1,3,4,4,5,6,7,8,10,10,11,12,12,12],[3,5,6,6,6,7,8,9,11,11,12,13,13,14],[4,6,7,7,8,9,10,11,13,13,14,14,14,15]];
MyArray[15] = [[5,5,5,5,5,5,5,5,5,5,5,5,5,5],[5,5,5,5,5,5,5,5,5,5,5,5,5,5],[5,5,5,5,5,5,5,5,5,5,5,5,5,5],[5,5,5,5,5,5,5,5,5,5,5,5,5,5],[5,5,5,5,5,5,5,5,5,5,5,5,5,5]];

//selection additive/subtractive + reset
$('#selector').change(function() {
	$('.hideornot').hide();
	$('.' + $(this).val()).show();
	$('.prefix').text('(select) ');
	$('.glyph-lvl').text('Level 0 - 0');
	$('.suffix').text(' (select)');
	$('.glyph-name').css({'color':"#ccc"});
	$('.desc1').text('');
	$('.desc2').text('');
	$('.desc3').text('');
	$('.effect-value2').text('');	
	$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/noglyph.png)");
	$(pot).prop('checked', false);
	$(ess).prop('checked', false);
	$(asp).prop('checked', false);
	table = 0;
	quality = 0;
	level = 0;
});

//potency rune prefix, glyph level, level array
$(pot).click(function() {
	if(this.checked) {
		$('.prefix').text($(this).attr('data-prefix'));
		$('.glyph-lvl').text($(this).attr('data-level'));
		level = parseInt($(this).val(), 10);
	}
});

//aspect rune change color, get quality array
$(asp).click(function() {
	if(this.checked) {
		$('.glyph-name').css({'color':$(this).attr('data-quality-color')});
		quality = parseInt($(this).val(), 10);
	};
});

//essence rune add suffix, get table, glyph image
$(ess).click(function () {
	if(this.checked) {
		$('.suffix').text($(this).attr('data-suffix'));
		table = parseInt($(this).val(), 10);
	};
	//recipes
	if($(this).attr('data-suffix') === " Health Regen" || $(this).attr('data-suffix') === " Stamina Regen" || $(this).attr('data-suffix') === " Magicka Regen") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/jewelryglyph.png)");
		$('.desc1').text(add);
		//$('.effect-value').text('x');
		$('.desc2').text($(this).attr('data-desc') + " Recovery");
		$('.effect-value2').text('');
		$('.desc3').text('');
	} else if ($(this).attr('data-suffix') === " Increase Magical Harm" || $(this).attr('data-suffix') === " Increase Physical Harm") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/jewelryglyph.png)");
		$('.desc1').text(add);
		$('.desc2').text($(this).attr('data-desc') + " Damage");
		$('.effect-value2').text('');
		$('.desc3').text('');
	} else if ($(this).attr('data-suffix') === " Potion Boost" || $(this).attr('data-suffix') === " Bashing") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/jewelryglyph.png)");
		$('.desc1').text(increase + $(this).attr('data-desc'));
		$('.desc2').text('');
		$('.effect-value2').text('');
		$('.desc3').text('');
	} else if ($(this).attr('data-suffix') === " Frost" || $(this).attr('data-suffix') === " Foulness" || $(this).attr('data-suffix') === " Poison" || $(this).attr('data-suffix') === " Shock" || $(this).attr('data-suffix') === " Decrease Health" || $(this).attr('data-suffix') === " Flame") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/armorglyph.png)");
		$('.desc1').text(deal);
		$('.desc2').text($(this).attr('data-desc'));
		$('.effect-value2').text('');
		$('.desc3').text('');
	} else if ($(this).attr('data-suffix') === " Frost Resist" || $(this).attr('data-suffix') === " Disease Resist" || $(this).attr('data-suffix') === " Poison Resist" || $(this).attr('data-suffix') === " Decrease Spell Harm" || $(this).attr('data-suffix') === " Shock Resist" || $(this).attr('data-suffix') === " Fire Resist" || $(this).attr('data-suffix') === " Decrease Physical Harm") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/jewelryglyph.png)");
		$('.desc1').text(add);
		$('.desc2').text($(this).attr('data-desc'));
		$('.effect-value2').text('');
		$('.desc3').text('');
	} else if ($(this).attr('data-suffix') === " Reduce Spell Cost" || $(this).attr('data-suffix') === " Reduce Feat Cost") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/jewelryglyph.png)");
		$('.desc1').text(reduce + $(this).attr('data-desc'));
		$('.desc2').text('');
		$('.effect-value2').text('');
		$('.desc3').text('');
	} else if ($(this).attr('data-suffix') === " Crushing" || $(this).attr('data-suffix') === " Weakening") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/armorglyph.png)");
		$('.desc1').text(reduce + $(this).attr('data-desc'));
		$('.desc2').text(' for ');
		$('.effect-value2').text('5');
		$('.desc3').text(' seconds');
	} else if ($(this).attr('data-suffix') === " Potion Speed") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/jewelryglyph.png)");
		$('.desc1').text(reduce + $(this).attr('data-desc'));
		$('.desc2').text(' seconds');
		$('.effect-value2').text('');
		$('.desc3').text('');
	} else if ($(this).attr('data-suffix') === " Shielding") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/jewelryglyph.png)");
		$('.desc1').text(reduce + $(this).attr('data-desc'));
		$('.desc2').text('');
		$('.effect-value2').text('5');
		$('.desc3').text('');
	} else if ($(this).attr('data-suffix') === " Rage") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/armorglyph.png)");
		$('.desc1').text(increase + $(this).attr('data-desc'));
		$('.desc2').text(' for ');
		$('.effect-value2').text('5');
		$('.desc3').text(' seconds');
	} else if ($(this).attr('data-suffix') === " Hardening") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/armorglyph.png)");
		$('.desc1').text('Grants a ');
		$('.desc2').text(' point Damage Shield for ');
		$('.effect-value2').text('5');
		$('.desc3').text(' seconds');
	} else if($(this).attr('data-suffix') === " Absorb Health" || $(this).attr('data-suffix') === " Absorb Magicka" || $(this).attr('data-suffix') === " Absorb Stamina") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/armorglyph.png)");
		$('.desc1').text(deal);
		$('.desc2').text(' Magic Damage and recovers ');
		$('.effect-value2').text(MyArray[14][quality][level]);
		$('.desc3').text($(this).attr('data-desc'));
	}
	//special
	if($(this).attr('data-suffix') === "Stamina" || $(this).attr('data-suffix') === "Magicka") {
		var checkedPot = $('input.potency-rune:checked');
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/armorglyph.png)");
		$('.desc1').text(add);
		$('.desc2').text(' Max ' + $(this).attr('data-suffix'));
	} else if($(this).attr('data-suffix') === " Health") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/armorglyph.png)");
		$('.desc1').text(add);
		$('.desc2').text(' Max ' + $(this).attr('data-suffix'));
	}
});
//global click update important
$(document).click(function() {
    $('.effect-value').text(MyArray[table][quality][level]);
});