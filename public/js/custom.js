(function ($) {
    ("use strict");


    //Add text/percentage inside the doughnut chart using Chart.js
    Chart.pluginService.register({
        beforeDraw: function (chart) {
            if (chart.config.options.percentageTest.center) {
                // Get ctx from string
                var ctx = chart.chart.ctx;

                // Get options from the center object in options
                var centerConfig = chart.config.options.percentageTest.center;
                var fontStyle =
                    centerConfig.fontStyle || "'Montserrat', sans-serif";
                var txt = centerConfig.text;
                var color = centerConfig.color || "#000";
                var maxFontSize = centerConfig.maxFontSize || 60;
                var sidePadding = centerConfig.sidePadding || 15;
                var sidePaddingCalculated =
                    (sidePadding / 100) * (chart.innerRadius * 2);
                // Start with a base font of 30px
                ctx.font = "50px " + fontStyle;

                // Get the width of the string and also the width of the element minus 10 to give it 5px side padding
                var stringWidth = ctx.measureText(txt).width;
                var elementWidth =
                    chart.innerRadius * 2 - sidePaddingCalculated;

                // Find out how much the font can grow in width.
                var widthRatio = elementWidth / stringWidth;
                var newFontSize = Math.floor(30 * widthRatio);
                var elementHeight = chart.innerRadius * 2;

                // Pick a new font size so it will not be larger than the height of label.
                var fontSizeToUse = Math.min(
                    newFontSize,
                    elementHeight,
                    maxFontSize
                );
                var minFontSize = centerConfig.minFontSize;
                var lineHeight = centerConfig.lineHeight || 15;
                var wrapText = false;

                if (minFontSize === undefined) {
                    minFontSize = 10;
                }

                if (minFontSize && fontSizeToUse < minFontSize) {
                    fontSizeToUse = minFontSize;
                    wrapText = true;
                }

                // Set font settings to draw it correctly.
                ctx.textAlign = "center";
                ctx.textBaseline = "middle";
                var centerX =
                    (chart.chartArea.left + chart.chartArea.right) / 2;
                var centerY =
                    (chart.chartArea.top + chart.chartArea.bottom) / 2;
                ctx.font = fontSizeToUse + "px " + fontStyle;
                ctx.fillStyle = color;

                if (!wrapText) {
                    ctx.fillText(txt, centerX, centerY);
                    return;
                }

                var words = txt.split(" ");
                var line = "";
                var lines = [];

                // Break words up into multiple lines if necessary
                for (var n = 0; n < words.length; n++) {
                    var testLine = line + words[n] + " ";
                    var metrics = ctx.measureText(testLine);
                    var testWidth = metrics.width;
                    if (testWidth > elementWidth && n > 0) {
                        lines.push(line);
                        line = words[n] + " ";
                    } else {
                        line = testLine;
                    }
                }

                // Move the center up depending on line height and number of lines
                centerY -= (lines.length / 2) * lineHeight;

                for (var n = 0; n < lines.length; n++) {
                    ctx.fillText(lines[n], centerX, centerY);
                    centerY += lineHeight;
                }
                //Draw text in center
                ctx.fillText(line, centerX, centerY);
            }
        },
    });

    // ------------DATATABLES-------------
    $('div.dataTables_filter input').addClass('form-control-search');

    $(function() {
        $input = $(".dataTables_filter").find("[type='search']");

        $input.parent().contents().filter(function() {
            return this.nodeType == 3 // here means return all node type text (textNode)
        }).each(function() {
            this.textContent = this.textContent.replace('Search:', '');
        });
        
        $input.before($("<i class='fas fa-search'></i>"));

    })

    // ------------step-wizard-------------
    $(document).ready(function () {
        $(".nav-tabs > li a[title]").tooltip();

        //Wizard
        $('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
            var target = $(e.target);

            if (target.parent().hasClass("disabled")) {
                return false;
            }
        });

        $(".next-step").click(function (e) {
            var active = $(".wizard .nav-tabs li.active");
            active.next().removeClass("disabled");
            nextTab(active);
        });
        $(".prev-step").click(function (e) {
            var active = $(".wizard .nav-tabs li.active");
            prevTab(active);

            $(".wizard.main").removeClass("d-none");
            $(".wizard.inner").addClass("d-none");
        });
    });

    function nextTab(elem) {
        $(elem).next().find('a[data-toggle="tab"]').click();
    }
    function prevTab(elem) {
        $(elem).prev().find('a[data-toggle="tab"]').click();
    }

    $(".nav-tabs").on("click", "li", function () {
        $(".nav-tabs li.active").removeClass("active");
        $(this).addClass("active");
    });

    //SWTICH SELECTED SDG's / QUESTIONS
    $(function () {
        $("#sdg-options").change(function () {
            $(".selected-sdgs").hide();
            $(".selected-sdgs-active").hide();
            $("#" + $(this).val()).show();
        });
    });

    $("[class^=popup]").click(function () {
        $(".content-" + this.className).toggleClass("hide");
        $(".content-" + this.className).toggleClass("d-block");

        $(".wizard.main").addClass("d-none");
    });
})(jQuery);

function readURL(input, id) {
    id = id || '#modal-preview';
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(id).attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        $('#modal-preview').removeClass('hidden');
        $('#start').hide();
    }
}