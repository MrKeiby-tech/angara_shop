BX.namespace("BX.Iblock.Catalog");

BX.Iblock.Catalog.CompareClass = (function () {
	var CompareClass = function (wrapObjId) {
		this.wrapObjId = wrapObjId;
	};

	CompareClass.prototype.MakeAjaxAction = function (url, refresh) {
		BX.showWait(BX(this.wrapObjId));
		BX.ajax.post(
			url,
			{
				ajax_action: "Y",
			},
			BX.proxy(function (result) {
				BX(this.wrapObjId).innerHTML = result;
				if (typeof refresh !== undefined) {
					getActualBasket("", "Compare");
					if ($("#compare_fly").length) {
						jsAjaxUtil.InsertDataToNode(
							arMaxOptions["SITE_DIR"] + "ajax/show_compare_preview_fly.php",
							"compare_fly",
							false
						);
					}
				}
				BX.closeWait();
				var eventdata = { action: "ajaxContentLoaded" };
				BX.onCustomEvent("onCompleteAction", [eventdata]);
				setBasketStatusBtn();
			}, this)
		);
	};

	return CompareClass;
})();

$(document).on("change", ".catalog-compare__switch #compare_diff", function () {
	var linksDiff = $(this).closest(".catalog-compare__top").find(".tabs-head"),
		url = "";

	if ($(this).is(":checked")) {
		url = linksDiff.find("li:eq(1) a").data("href");
	} else {
		url = linksDiff.find("li:eq(0) a").data("href");
	}

	BX.showWait(BX("bx_catalog_compare_block"));
	$.ajax({
		url: url,
		data: { ajax_action: "Y" },
		success: function (html) {
			history.pushState(null, null, url);
			$("#bx_catalog_compare_block").html(html);
			BX.closeWait();
			var eventdata = { action: "ajaxContentLoaded" };
			BX.onCustomEvent("onCompleteAction", [eventdata]);
			setBasketStatusBtn();
		},
	});
});

function tableEqualHeight($sliderProps, $sliderPropsItems) {
	var arHeights = [];

	$sliderProps.find(".catalog-compare__prop-line").removeAttr("style");

	for (
		var i = 0;
		i <
		$sliderProps.find(".owl-item:first-child .catalog-compare__prop-line")
			.length;
		i++
	) {
		arHeights[i] = 0;
	}

	//get max height
	$sliderPropsItems.each(function (i, elementI) {
		$(this)
			.find(".catalog-compare__prop-line")
			.each(function (j, elementJ) {
				if ($(this).outerHeight() > arHeights[j])
					arHeights[j] = $(this).outerHeight(true);
			});
	});

	// set height
	$sliderPropsItems.each(function (i, elementI) {
		$(this)
			.find(".catalog-compare__prop-line")
			.each(function (j, elementJ) {
				$(this).css("height", arHeights[j]);
			});
	});
}

BX.addCustomEvent("onSliderInitialized", function (eventdata) {
	if (eventdata) {
		var slider = eventdata.slider;
		if (slider) {
			$(".catalog-compare__inner").removeClass("loading");
		}
	}
});

$(document).on("click", ".compare-sections__tab-item", function () {
	let th = $(this);
	if (!th.hasClass("active")) {
		let curSectionId = th.find("[data-section-id]").attr("data-section-id");
		$(".compare-sections__tab-item").removeClass("active");
		$(".compare-sections__item").removeClass("active");
		th.addClass("active");
		if (curSectionId) {
			$(
				".compare-sections__item" + "[data-section-id=" + curSectionId + "]"
			).addClass("active");
			$.cookie("compare_section", curSectionId);
		}
	}
});



