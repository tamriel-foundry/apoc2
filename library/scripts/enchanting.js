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
var craftExp = 0;
var craftExpQuality = 0;

var MyArray = [];
//Default values 0
MyArray[0] = [
	["?","?","?","?","?","?","?","?","?","?","?","?","?","?"],
	["?","?","?","?","?","?","?","?","?","?","?","?","?","?"],
	["?","?","?","?","?","?","?","?","?","?","?","?","?","?"],
	["?","?","?","?","?","?","?","?","?","?","?","?","?","?"],
	["?","?","?","?","?","?","?","?","?","?","?","?","?","?"]
];
// Adds x Maximum Magicka/Stamina. (armor)
MyArray[1] = [
	[ 70, 70,140,140,210,210,280,280,350,420,420,420,420,490],
	[ 97, 97,167,167,237,237,307,307,377,447,447,447,447,517],
	[130,130,200,200,270,270,340,340,410,480,480,480,480,550],
	[170,170,240,240,310,310,380,380,450,520,520,520,520,590],
	[217,217,287,287,357,357,427,427,497,497,567,567,567,637]
];
// Adds x Health. (armor)
MyArray[2] = [
	[ 77, 77,154,154,231,231,308,308,385,462,462,462,462,539],
	[106,106,183,183,260,260,337,337,414,491,491,491,491,568],
	[143,143,220,220,297,297,374,374,451,528,528,528,528,605],
	[187,187,264,264,341,341,418,418,495,572,572,572,572,649],
	[238,238,315,315,392,392,469,469,546,623,623,623,623,700]
];
// Deals x [Type] Damage. (weapon)
MyArray[3] = [
	[ 30, 40, 70,100,120,150,180,210,240,300,310,320,330,360],
	[ 40, 60, 90,120,140,170,200,230,260,320,330,340,350,380],
	[ 50, 80,110,140,160,190,220,250,280,340,350,360,370,400],
	[ 90,120,150,180,200,230,260,290,320,380,390,400,410,440],
	[140,170,200,230,250,280,310,340,370,430,440,450,460,490]
];
// Increase your Weapon Damage by x for 5 seconds. (weapon)
// Reduce target Weapon Damage by x for 5 seconds. (weapon)
MyArray[4] = [
	[ 7, 7,14,14,21,21,28,28,35,42,42,42,42,49],
	[10,10,17,17,24,24,31,31,38,45,45,45,45,52],
	[13,13,20,20,27,27,34,34,41,48,48,48,48,55],
	[17,17,24,24,31,31,38,38,45,52,52,52,52,55],
	[22,22,29,29,36,36,43,43,50,57,57,57,57,64]
];
//Reduce target's armor by x for 5 seconds. (weapon)
//Adds x Spell/Physical Resistance. (jewelry)
MyArray[5] = [
	[  50,  50, 130, 130, 210, 210, 290, 290, 370, 450, 450, 450, 450, 530],
	[ 156, 156, 236, 236, 316, 316, 396, 396, 476, 556, 556, 556, 556, 636],
	[ 290, 290, 370, 370, 450, 450, 530, 530, 610, 690, 690, 690, 690, 770],
	[ 450, 450, 530, 530, 610, 610, 690, 690, 770, 850, 850, 850, 850, 930],
	[ 636, 636, 716, 716, 796, 796, 876, 876, 956,1036,1036,1036,1036,1116]
];
//Deals x Magicka Damage and restores ? Magicka/Stamina/Health. (weapon)
//Deals x unresistable damage. (weapon)
MyArray[6] = [
	[ 22, 30, 52, 75, 90,112,135,157,180,255,232,240,248,270],
	[ 30, 45, 64, 90,105,127,150,172,195,240,247,255,262,285],
	[ 37, 60, 82,105,120,142,165,187,210,255,262,270,277,300],
	[ 67, 90,112,135,150,172,185,217,240,285,292,300,307,330],
	[105,127,150,172,187,210,232,255,277,322,330,337,345,367]
];
//Adds x Weapon/Spell Damage. (jewelry)
MyArray[7] = [
	[ 7, 7,14,14,21,21,28,28,35,42,42,42,42,49],
	[10,10,17,17,24,24,31,31,38,45,45,45,45,52],
	[13,13,20,20,27,27,34,34,41,48,48,48,48,55],
	[17,17,24,24,31,31,38,38,45,52,52,52,52,59],
	[22,22,29,29,36,36,43,43,50,57,57,57,57,64]
];
//Adds x Magicka/Stamina/Health Recovery. (jewelry)
MyArray[8] = [
	[ 7, 7,14,14,21,21,28,28,35,42,42,42,42,49],
	[10,10,17,17,24,24,31,31,38,45,45,45,45,52],
	[13,13,20,20,27,27,34,34,41,48,48,48,48,55],
	[17,17,24,24,31,31,38,38,45,52,52,52,52,59],
	[22,22,29,29,36,36,43,43,50,57,57,57,57,64]
];
//Increase bash damage by x. (jewelry)
MyArray[9] = [
	[ 2, 5, 7,10,12,15,17,20,22,25,26,27,28,31],
	[ 4, 6, 9,11,14,16,19,21,24,26,27,28,29,32],
	[ 5, 8,10,13,15,18,20,23,25,28,29,30,31,34],
	[ 7, 9,12,14,17,19,22,24,27,29,30,31,32,35],
	[ 8,11,13,16,18,21,23,26,28,31,32,33,34,37]
];
//Increase the duration of potion effects by x seconds. (jewelry)
MyArray[10] = [
	[0.7,0.9,1.1,1.3,1.5,1.7,1.9,2.1,2.3,2.5,2.6,2.7,2.7,3.0],
	[0.8,1.0,1.2,1.4,1.6,1.8,2.0,2.2,2.4,2.6,2.7,2.8,2.9,3.1],
	[0.9,1.1,1.3,1.5,1.7,1.9,2.1,2.3,2.5,2.7,2.8,2.9,3.0,3.2],
	[1.1,1.3,1.5,1.7,1.9,2.1,2.3,2.5,2.7,2.9,2.9,3.0,3.1,3.3],
	[1.2,1.4,1.6,1.8,2.0,2.2,2.4,2.6,2.8,3.0,3.1,3.1,3.2,3.5]
];
//Adds x [Type] Resistance. (jewelry)
MyArray[11] = [
	[  50,  50, 210, 210, 370, 370, 530, 530, 690, 850, 850, 850, 850,1010],
	[ 263, 263, 423, 423, 583, 583, 743, 743, 903,1063,1063,1063,1063,1223],
	[ 530, 530, 690, 690, 850, 850,1010,1010,1070,1330,1330,1330,1330,1490],
	[ 850, 850,1010,1010,1070,1070,1330,1330,1490,1650,1650,1650,1650,1810],
	[1123,1123,1383,1383,1543,1543,1703,1703,1863,2023,2023,2023,2023,2183]
];
//Reduce Stamina/Magicka cost of abilities by x. (jewelry)
//Reduce cost of blocking by y. (jewelry)
MyArray[12] = [
	[ 30, 30, 51, 51, 72, 72, 93, 93,114,135,135,135,135,156],
	[ 38, 38, 59, 59, 80, 80,101,101,122,143,143,143,143,164],
	[ 48, 48, 69, 69, 90 ,90,111,111,132,153,153,153,153,174],
	[ 60, 60, 81, 81,102,102,123,123,144,165,165,165,165,186],
	[ 74, 74, 95, 95,116,116,137,137,158,179,179,179,179,200]
];
//Reduce cost of bash by x (and reduce cost of blocking by y).
MyArray[13] = [
	[ 45, 45, 76, 76,108,108,139,139,171,202,202,202,202,234],
	[ 57, 57, 88, 88,120,120,151,151,183,214,214,214,214,246],
	[ 72, 72,103,103,135,135,166,166,198,229,229,229,229,261],
	[ 90, 90,121,121,153,153,184,184,216,247,247,247,247,279],
	[111,111,142,142,174,174,205,205,237,268,268,268,268,300]
];
// + Restore x from magicka damage dealt
MyArray[14] = [
	[ 10, 13, 23, 34, 40, 51, 61, 71, 81,102,105,108,112,122],
	[ 13, 20, 30, 40, 47, 57, 68, 78, 88,108,122,115,119,129],
	[ 17, 27, 37, 47, 54, 64, 74, 85, 95,115,119,122,125,136],
	[ 30, 40, 51, 61, 68, 78, 88, 98,108,129,132,136,139,149],
	[ 47, 57, 68, 78, 85, 95,105,115,125,146,149,153,156,166]
];
//Grants a x point Damage Shield for 5 seconds. (weapon)
MyArray[15] = [
	[  50,  50, 130, 130, 210, 210, 290, 290, 370, 450, 450, 450, 450, 530],
	[ 156, 156, 236, 236, 316, 316, 396, 396, 476, 556, 556, 556, 556, 636],
	[ 290, 290, 370, 370, 450, 450, 530, 530, 610, 690, 690, 690, 690, 770],
	[ 450, 450, 530, 530, 610, 610, 690, 690, 770, 850, 850, 850, 850, 930],
	[ 636, 636, 716, 716, 796, 796, 876, 876, 956,1036,1036,1036,1036,1116]
];
//second cooldown
MyArray[16] = [
	[5,5,5,5,5,5,5,5,5,5,5,5,5,5],
	[5,5,5,5,5,5,5,5,5,5,5,5,5,5],
	[5,5,5,5,5,5,5,5,5,5,5,5,5,5],
	[5,5,5,5,5,5,5,5,5,5,5,5,5,5],
	[5,5,5,5,5,5,5,5,5,5,5,5,5,5]
];

var craftExpArray = [];
craftExpArray[0] = [165,329,548,987,2632];
craftExpArray[1] = [232,464,773,1392,3715];
craftExpArray[2] = [307,614,1023,1842,4912];
craftExpArray[3] = [403,806,1343,2418,6448];
craftExpArray[4] = [487,974,1623,2922,7792];
craftExpArray[5] = [592,1184,1973,3552,9472];
craftExpArray[6] = [692,1384,2307,4152,11072];
craftExpArray[7] = [814,1628,2713,4884,13024];
craftExpArray[8] = [969,1938,3230,5814,15504];
craftExpArray[9] = [1200,2400,4000,7200,19200];
craftExpArray[10] = [1281,2562,4270,7686,20496];
craftExpArray[11] = [1362,2724,4540,8172,21792];
craftExpArray[12] = [1483,2966,4943,8898,23728];
craftExpArray[13] = [1605,3210,5350,9630,25680];
craftExpArray[14] = ["Crafting Exp"];

var extractExpArray = [];
extractExpArray[0] = [313,625,1042,1875,2500];
extractExpArray[1] = [441,882,1470,2646,3528];
extractExpArray[2] = [586,1172,1953,3516,4688];
extractExpArray[3] = [768,1536,2560,4608,6144];
extractExpArray[4] = [928,1856,3093,5568,7424];
extractExpArray[5] = [1127,2254,3757,6762,9016];
extractExpArray[6] = [1318,2636,4393,7908,10544];
extractExpArray[7] = [1552,3104,5173,9312,12416];
extractExpArray[8] = [1845,3690,6150,11070,14760];
extractExpArray[9] = [2284,4568,7613,13704,18272];
extractExpArray[10] = [2437,4874,8123,14622,19496];
extractExpArray[11] = [2591,5182,8637,15546,20728];
extractExpArray[12] = [2744,5488,9147,16464,21952];
extractExpArray[13] = [3282,6564,10940,19692,26256];
extractExpArray[14] = ["Extraction Exp"];

//selection additive/subtractive + reset
$('#selector').change(function() {
	$('.hideornot').hide();
	$('.' + $(this).val()).show();
	$('.prefix').text('(select) ');
	$('.glyph-lvl').text('Level 0 - 0');
	$('.glyph-type').text('Type');
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
	craftExp = 14;
	craftExpQuality = 0;
});

//potency rune prefix, glyph level, level array
$(pot).click(function() {
	if(this.checked) {
		$('.prefix').text($(this).attr('data-prefix'));
		$('.glyph-lvl').text($(this).attr('data-level'));
		level = parseInt($(this).val(), 10);
		craftExp = parseInt($(this).val(), 10);
	}
});

//aspect rune change color, get quality array
$(asp).click(function() {
	if(this.checked) {
		$('.glyph-name').css({'color':$(this).attr('data-quality-color')});
		quality = parseInt($(this).val(), 10);
		craftExpQuality = parseInt($(this).val(), 10);
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
		$('.glyph-type').text('(Jewelry)');
		$('.desc1').text(add);
		//$('.effect-value').text('x');
		$('.desc2').text($(this).attr('data-desc') + " Recovery");
		$('.effect-value2').text('');
		$('.desc3').text('');
	} else if ($(this).attr('data-suffix') === " Increase Magical Harm" || $(this).attr('data-suffix') === " Increase Physical Harm") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/jewelryglyph.png)");
		$('.glyph-type').text('(Jewelry)');
		$('.desc1').text(add);
		$('.desc2').text($(this).attr('data-desc') + " Damage");
		$('.effect-value2').text('');
		$('.desc3').text('');
	} else if ($(this).attr('data-suffix') === " Potion Boost" || $(this).attr('data-suffix') === " Bashing") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/jewelryglyph.png)");
		$('.glyph-type').text('(Jewelry)');
		$('.desc1').text(increase + $(this).attr('data-desc'));
		$('.desc2').text('');
		$('.effect-value2').text('');
		$('.desc3').text(' seconds');
	} else if ($(this).attr('data-suffix') === " Frost" || $(this).attr('data-suffix') === " Foulness" || $(this).attr('data-suffix') === " Poison" || $(this).attr('data-suffix') === " Shock" || $(this).attr('data-suffix') === " Decrease Health" || $(this).attr('data-suffix') === " Flame") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/weaponglyph.png)");
		$('.glyph-type').text('(Weapon)');
		$('.desc1').text(deal);
		$('.desc2').text($(this).attr('data-desc'));
		$('.effect-value2').text('');
		$('.desc3').text('');
	} else if ($(this).attr('data-suffix') === " Frost Resist" || $(this).attr('data-suffix') === " Disease Resist" || $(this).attr('data-suffix') === " Poison Resist" || $(this).attr('data-suffix') === " Decrease Spell Harm" || $(this).attr('data-suffix') === " Shock Resist" || $(this).attr('data-suffix') === " Fire Resist" || $(this).attr('data-suffix') === " Decrease Physical Harm") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/jewelryglyph.png)");
		$('.glyph-type').text('(Jewelry)');
		$('.desc1').text(add);
		$('.desc2').text($(this).attr('data-desc'));
		$('.effect-value2').text('');
		$('.desc3').text('');
	} else if ($(this).attr('data-suffix') === " Reduce Spell Cost" || $(this).attr('data-suffix') === " Reduce Feat Cost") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/jewelryglyph.png)");
		$('.glyph-type').text('(Jewelry)');
		$('.desc1').text(reduce + $(this).attr('data-desc'));
		$('.desc2').text('');
		$('.effect-value2').text('');
		$('.desc3').text('');
	} else if ($(this).attr('data-suffix') === " Crushing" || $(this).attr('data-suffix') === " Weakening") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/weaponglyph.png)");
		$('.glyph-type').text('(Weapon)');
		$('.desc1').text(reduce + $(this).attr('data-desc'));
		$('.desc2').text(' for ');
		$('.effect-value2').text('5');
		$('.desc3').text(' seconds');
	} else if ($(this).attr('data-suffix') === " Potion Speed") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/jewelryglyph.png)");
		$('.glyph-type').text('(Jewelry)');
		$('.desc1').text(reduce + $(this).attr('data-desc'));
		$('.desc2').text(' seconds');
		$('.effect-value2').text('');
		$('.desc3').text('');
	} else if ($(this).attr('data-suffix') === " Shielding") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/jewelryglyph.png)");
		$('.glyph-type').text('(Jewelry)');
		$('.desc1').text('Reduce cost of bash by ');
		$('.desc2').text(' and reduce the cost of blocking by ');
		$('.effect-value2').text(MyArray[12][quality][level]);
		$('.desc3').text('');
	} else if ($(this).attr('data-suffix') === " Rage") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/weaponglyph.png)");
		$('.glyph-type').text('(Weapon)');
		$('.desc1').text(increase + $(this).attr('data-desc'));
		$('.desc2').text(' for ');
		$('.effect-value2').text('5');
		$('.desc3').text(' seconds');
	} else if ($(this).attr('data-suffix') === " Hardening") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/weaponglyph.png)");
		$('.glyph-type').text('(Weapon)');
		$('.desc1').text('Grants a ');
		$('.desc2').text(' point Damage Shield for ');
		$('.effect-value2').text('5');
		$('.desc3').text(' seconds');
	} else if($(this).attr('data-suffix') === " Absorb Health" || $(this).attr('data-suffix') === " Absorb Magicka" || $(this).attr('data-suffix') === " Absorb Stamina") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/weaponglyph.png)");
		$('.glyph-type').text('(Weapon)');
		$('.desc1').text(deal);
		$('.desc2').text(' Magic Damage and restores ');
		$('.effect-value2').text(MyArray[14][quality][level]);
		$('.desc3').text($(this).attr('data-desc'));
	}
	//Armor glyphs
	if($(this).attr('data-suffix') === "Stamina" || $(this).attr('data-suffix') === "Magicka") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/armorglyph.png)");
		$('.glyph-type').text('(Armor)');
		$('.desc1').text(add);
		$('.desc2').text(' Max ' + $(this).attr('data-suffix'));
	} else if($(this).attr('data-suffix') === " Health") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/armorglyph.png)");
		$('.glyph-type').text('(Armor)');
		$('.desc1').text(add);
		$('.desc2').text(' Max ' + $(this).attr('data-suffix'));
	}
});
//global click update important
$(document).click(function() {
    $('.effect-value').text(MyArray[table][quality][level]);
    $('.glyph-crafting-exp').text(craftExpArray[craftExp][craftExpQuality]);
    $('.glyph-extraction-exp').text(extractExpArray[craftExp][craftExpQuality]);
});