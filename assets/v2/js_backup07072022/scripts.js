"use strict";

!function (NioApp, $) {
  "use strict";

  NioApp.Package.name = "DashLite";
  NioApp.Package.version = "2.3";
  var $win = $(window),
    $body = $('body'),
    $doc = $(document),
    //class names
    _body_theme = 'nio-theme',
    _menu = 'nk-menu',
    _mobile_nav = 'mobile-menu',
    _header = 'nk-header',
    _header_menu = 'nk-header-menu',
    _sidebar = 'nk-sidebar',
    _sidebar_mob = 'nk-sidebar-mobile',
    _app_sidebar = 'nk-apps-sidebar',
    //breakpoints
    _break = NioApp.Break;

  function extend(obj, ext) {
    Object.keys(ext).forEach(function (key) {
      obj[key] = ext[key];
    });
    return obj;
  } // ClassInit @v1.0


  NioApp.ClassBody = function () {
    NioApp.AddInBody(_sidebar);
    NioApp.AddInBody(_app_sidebar);
  }; // ClassInit @v1.0


  NioApp.ClassNavMenu = function () {
    NioApp.BreakClass('.' + _header_menu, _break.lg, {
      timeOut: 0
    });
    NioApp.BreakClass('.' + _sidebar, _break.lg, {
      timeOut: 0,
      classAdd: _sidebar_mob
    });
    $win.on('resize', function () {
      NioApp.BreakClass('.' + _header_menu, _break.lg);
      NioApp.BreakClass('.' + _sidebar, _break.lg, {
        classAdd: _sidebar_mob
      });
    });
  }; // Code Prettify @v1.0


  NioApp.Prettify = function () {
    window.prettyPrint && prettyPrint();
  }; // Copied @v1.0


  NioApp.Copied = function () {
    var clip = '.clipboard-init',
      target = '.clipboard-text',
      sclass = 'clipboard-success',
      eclass = 'clipboard-error'; // Feedback

    function feedback(el, state) {
      var $elm = $(el),
        $elp = $elm.parent(),
        copy = {
          text: 'Copy',
          done: 'Copied',
          fail: 'Failed'
        },
        data = {
          text: $elm.data('clip-text'),
          done: $elm.data('clip-success'),
          fail: $elm.data('clip-error')
        };
      copy.text = data.text ? data.text : copy.text;
      copy.done = data.done ? data.done : copy.done;
      copy.fail = data.fail ? data.fail : copy.fail;
      var copytext = state === 'success' ? copy.done : copy.fail,
        addclass = state === 'success' ? sclass : eclass;
      $elp.addClass(addclass).find(target).html(copytext);
      setTimeout(function () {
        $elp.removeClass(sclass + ' ' + eclass).find(target).html(copy.text).blur();
        $elp.find('input').blur();
      }, 2000);
    } // Init ClipboardJS


    if (ClipboardJS.isSupported()) {
      var clipboard = new ClipboardJS(clip);
      clipboard.on('success', function (e) {
        feedback(e.trigger, 'success');
        e.clearSelection();
      }).on('error', function (e) {
        feedback(e.trigger, 'error');
      });
    } else {
      $(clip).css('display', 'none');
    }

    ;
  }; // CurrentLink Detect @v1.0


  NioApp.CurrentLink = function () {
    var _link = '.nk-menu-link, .menu-link, .nav-link',
      _currentURL = window.location.href,
      fileName = _currentURL.substring(0, _currentURL.indexOf("#") == -1 ? _currentURL.length : _currentURL.indexOf("#")),
      fileName = fileName.substring(0, fileName.indexOf("?") == -1 ? fileName.length : fileName.indexOf("?"));

    $(_link).each(function () {
      var self = $(this),
        _self_link = self.attr('href');

      if (fileName.match(_self_link)) {
        self.closest("li").addClass('active current-page').parents().closest("li").addClass("active current-page");
        self.closest("li").children('.nk-menu-sub').css('display', 'block');
        self.parents().closest("li").children('.nk-menu-sub').css('display', 'block');
      } else {
        self.closest("li").removeClass('active current-page').parents().closest("li:not(.current-page)").removeClass("active");
      }
    });
  }; // PasswordSwitch @v1.0


  NioApp.PassSwitch = function () {
    NioApp.Passcode('.passcode-switch');
  }; // Toastr Message @v1.0 


  NioApp.Toast = function (msg, ttype, opt) {
    var ttype = ttype ? ttype : 'info',
      msi = '',
      ticon = ttype === 'info' ? 'ni ni-info-fill' : ttype === 'success' ? 'ni ni-check-circle-fill' : ttype === 'error' ? 'ni ni-cross-circle-fill' : ttype === 'warning' ? 'ni ni-alert-fill' : '',
      def = {
        position: 'bottom-right',
        ui: '',
        icon: 'auto',
        clear: false
      },
      attr = opt ? extend(def, opt) : def;
    attr.position = attr.position ? 'toast-' + attr.position : 'toast-bottom-right';
    attr.icon = attr.icon === 'auto' ? ticon : attr.icon ? attr.icon : '';
    attr.ui = attr.ui ? ' ' + attr.ui : '';
    msi = attr.icon !== '' ? '<span class="toastr-icon"><em class="icon ' + attr.icon + '"></em></span>' : '', msg = msg !== '' ? msi + '<div class="toastr-text">' + msg + '</div>' : '';

    if (msg !== "") {
      if (attr.clear === true) {
        toastr.clear();
      }

      var option = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": attr.position + attr.ui,
        "closeHtml": '<span class="btn-trigger">Close</span>',
        "preventDuplicates": true,
        "showDuration": "1500",
        "hideDuration": "1500",
        "timeOut": "2000",
        "toastClass": "toastr",
        "extendedTimeOut": "3000"
      };
      toastr.options = extend(option, attr);
      toastr[ttype](msg);
    }
  }; // Toggle Screen @v1.0


  NioApp.TGL.screen = function (elm) {
    if ($(elm).exists()) {
      $(elm).each(function () {
        var ssize = $(this).data('toggle-screen');

        if (ssize) {
          $(this).addClass('toggle-screen-' + ssize);
        }
      });
    }
  }; // Toggle Content @v1.0


  NioApp.TGL.content = function (elm, opt) {
    var toggle = elm ? elm : '.toggle',
      $toggle = $(toggle),
      $contentD = $('[data-content]'),
      toggleBreak = true,
      toggleCurrent = false,
      def = {
        active: 'active',
        content: 'content-active',
        "break": toggleBreak
      },
      attr = opt ? extend(def, opt) : def;
    NioApp.TGL.screen($contentD);
    $toggle.on('click', function (e) {
      toggleCurrent = this;
      NioApp.Toggle.trigger($(this).data('target'), attr);
      e.preventDefault();
    });
    $doc.on('mouseup', function (e) {
      if (toggleCurrent) {
        var $toggleCurrent = $(toggleCurrent),
          $s2c = $('.select2-container'),
          $dpd = $('.datepicker-dropdown'),
          $tpc = $('.ui-timepicker-container');

        if (!$toggleCurrent.is(e.target) && $toggleCurrent.has(e.target).length === 0 && !$contentD.is(e.target) && $contentD.has(e.target).length === 0 && !$s2c.is(e.target) && $s2c.has(e.target).length === 0 && !$dpd.is(e.target) && $dpd.has(e.target).length === 0 && !$tpc.is(e.target) && $tpc.has(e.target).length === 0) {
          NioApp.Toggle.removed($toggleCurrent.data('target'), attr);
          toggleCurrent = false;
        }
      }
    });
    $win.on('resize', function () {
      $contentD.each(function () {
        var content = $(this).data('content'),
          ssize = $(this).data('toggle-screen'),
          toggleBreak = _break[ssize];

        if (NioApp.Win.width > toggleBreak) {
          NioApp.Toggle.removed(content, attr);
        }
      });
    });
  }; // ToggleExpand @v1.0


  NioApp.TGL.expand = function (elm, opt) {
    var toggle = elm ? elm : '.expand',
      def = {
        toggle: true
      },
      attr = opt ? extend(def, opt) : def;
    $(toggle).on('click', function (e) {
      NioApp.Toggle.trigger($(this).data('target'), attr);
      e.preventDefault();
    });
  }; // Dropdown Menu @v1.0


  NioApp.TGL.ddmenu = function (elm, opt) {
    var imenu = elm ? elm : '.nk-menu-toggle',
      def = {
        active: 'active',
        self: 'nk-menu-toggle',
        child: 'nk-menu-sub'
      },
      attr = opt ? extend(def, opt) : def;
    $(imenu).on('click', function (e) {
      if (NioApp.Win.width < _break.lg || $(this).parents().hasClass(_sidebar)) {
        NioApp.Toggle.dropMenu($(this), attr);
      }

      e.preventDefault();
    });
  }; // Show Menu @v1.0


  NioApp.TGL.showmenu = function (elm, opt) {
    var toggle = elm ? elm : '.nk-nav-toggle',
      $toggle = $(toggle),
      $contentD = $('[data-content]'),
      toggleBreak = $contentD.hasClass(_header_menu) ? _break.lg : _break.xl,
      toggleOlay = _sidebar + '-overlay',
      toggleClose = {
        profile: true,
        menu: false
      },
      def = {
        active: 'toggle-active',
        content: _sidebar + '-active',
        body: 'nav-shown',
        overlay: toggleOlay,
        "break": toggleBreak,
        close: toggleClose
      },
      attr = opt ? extend(def, opt) : def;
    $toggle.on('click', function (e) {
      NioApp.Toggle.trigger($(this).data('target'), attr);
      e.preventDefault();
    });
    $doc.on('mouseup', function (e) {
      if (!$toggle.is(e.target) && $toggle.has(e.target).length === 0 && !$contentD.is(e.target) && $contentD.has(e.target).length === 0 && NioApp.Win.width < toggleBreak) {
        NioApp.Toggle.removed($toggle.data('target'), attr);
      }
    });
    $win.on('resize', function () {
      if (NioApp.Win.width < _break.xl || NioApp.Win.width < toggleBreak) {
        NioApp.Toggle.removed($toggle.data('target'), attr);
      }
    });
  }; // Animate FormSearch @v1.0


  NioApp.Ani.formSearch = function (elm, opt) {
    var def = {
      active: 'active',
      timeout: 400,
      target: '[data-search]'
    },
      attr = opt ? extend(def, opt) : def;
    var $elem = $(elm),
      $target = $(attr.target);

    if ($elem.exists()) {
      $elem.on('click', function (e) {
        e.preventDefault();
        var $self = $(this),
          the_target = $self.data('target'),
          $self_st = $('[data-search=' + the_target + ']'),
          $self_tg = $('[data-target=' + the_target + ']');

        if (!$self_st.hasClass(attr.active)) {
          $self_tg.add($self_st).addClass(attr.active);
          $self_st.find('input').focus();
        } else {
          $self_tg.add($self_st).removeClass(attr.active);
          setTimeout(function () {
            $self_st.find('input').val('');
          }, attr.timeout);
        }
      });
      $doc.on({
        keyup: function keyup(e) {
          if (e.key === "Escape") {
            $elem.add($target).removeClass(attr.active);
          }
        },
        mouseup: function mouseup(e) {
          if (!$target.find('input').val() && !$target.is(e.target) && $target.has(e.target).length === 0 && !$elem.is(e.target) && $elem.has(e.target).length === 0) {
            $elem.add($target).removeClass(attr.active);
          }
        }
      });
    }
  }; // Animate FormElement @v1.0


  NioApp.Ani.formElm = function (elm, opt) {
    var def = {
      focus: 'focused'
    },
      attr = opt ? extend(def, opt) : def;

    if ($(elm).exists()) {
      $(elm).each(function () {
        var $self = $(this);

        if ($self.val()) {
          $self.parent().addClass(attr.focus);
        }

        $self.on({
          focus: function focus() {
            $self.parent().addClass(attr.focus);
          },
          blur: function blur() {
            if (!$self.val()) {
              $self.parent().removeClass(attr.focus);
            }
          }
        });
      });
    }
  }; // Form Validate @v1.0


  NioApp.Validate = function (elm, opt) {
    if ($(elm).exists()) {
      $(elm).each(function () {
        var def = {
          errorElement: "span"
        },
          attr = opt ? extend(def, opt) : def;
        $(this).validate(attr);
      });
    }
  };

  NioApp.Validate.init = function () {
    NioApp.Validate('.form-validate', {
      errorElement: "span",
      errorClass: "invalid",
      errorPlacement: function errorPlacement(error, element) {
        if (element.parents().hasClass('input-group')) {
          error.appendTo(element.parent().parent());
        } else {
          error.appendTo(element.parent());
        }
      }
    });
  };

  // Dropzone @v1.1
  NioApp.Dropzone = function (elm, opt) {
    if ($(elm).exists()) {
      $(elm).each(function () {
        var maxFiles = $(elm).data('max-files'),
          maxFiles = maxFiles ? maxFiles : null;
        var maxFileSize = $(elm).data('max-file-size'),
          maxFileSize = maxFileSize ? maxFileSize : 256;
        var acceptedFiles = $(elm).data('accepted-files'),
          acceptedFiles = acceptedFiles ? acceptedFiles : null;
        var def = {
          autoDiscover: false,
          maxFiles: maxFiles,
          maxFilesize: maxFileSize,
          acceptedFiles: acceptedFiles
        },
          attr = opt ? extend(def, opt) : def;
        $(this).addClass('dropzone').dropzone(attr);
      });
    }
  }; // Dropzone Init @v1.0


  // // Dropzone Init @v1.0
  NioApp.Dropzone.init = function () {

    var request_number = $('#request_number').val();
    var upload_zone = $('#upload_zone').val();
    var table_id = '#table_documents_eapp';

    NioApp.Dropzone('.upload-zone', {
      url: "form/upload_documents/eapp/" + request_number,
      addRemoveLinks: true,
      init: function () {
        this.on("success", function (file, responseText) {
          this.removeFile(file);
          $(table_id).DataTable().ajax.reload();
        });
      }
    });
  };  // Wizard @v1.0


  NioApp.Wizard = function () {
    var $wizard = $(".nk-wizard").show();
    $wizard.steps({
      headerTag: ".nk-wizard-head",
      bodyTag: ".nk-wizard-content",
      labels: {
        finish: "Submit",
        next: "Next",
        previous: "Prev",
        loading: "Loading ..."
      },
      onStepChanging: function onStepChanging(event, currentIndex, newIndex) {
        // Allways allow previous action even if the current form is not valid!
        if (currentIndex > newIndex) {
          return true;
        } // Needed in some cases if the user went back (clean up)


        if (currentIndex < newIndex) {
          // To remove error styles
          $wizard.find(".body:eq(" + newIndex + ") label.error").remove();
          $wizard.find(".body:eq(" + newIndex + ") .error").removeClass("error");
        }

        $wizard.validate().settings.ignore = ":disabled,:hidden";
        return $wizard.valid();
      },
      onFinishing: function onFinishing(event, currentIndex) {
        $wizard.validate().settings.ignore = ":disabled";
        return $wizard.valid();
      },
      onFinished: function onFinished(event, currentIndex) {
        window.location.href = "#";
      }
    }).validate({
      errorElement: "span",
      errorClass: "invalid",
      errorPlacement: function errorPlacement(error, element) {
        error.appendTo(element.parent());
      }
    });
  }; // DataTable @1.1


  NioApp.DataTable = function (elm, opt) {
    if ($(elm).exists()) {
      $(elm).each(function () {
        var id = $(this).attr('id');
        var data = $(this).attr('data-ajaxsource');

        var auto_responsive = $(this).data('auto-responsive'),
          has_export = typeof opt.buttons !== 'undefined' && opt.buttons ? true : false;
        var export_title = $(this).data('export-title') ? $(this).data('export-title') : 'Export';
        var btn = has_export ? '<"dt-export-buttons d-flex align-center"<"dt-export-title d-none d-md-inline-block">B>' : '',
          btn_cls = has_export ? ' with-export' : '';
        var dom_normal = '<"row justify-between g-2' + btn_cls + '"<"col-7 col-sm-4 text-left"f><"col-5 col-sm-8 text-right"<"datatable-filter"<"d-flex justify-content-end g-2"' + btn + 'l>>>><"datatable-wrap my-3"t><"row align-items-center"<"col-7 col-sm-12 col-md-9"p><"col-5 col-sm-12 col-md-3 text-left text-md-right"i>>';
        var dom_separate = '<"row justify-between g-2' + btn_cls + '"<"col-7 col-sm-4 text-left"f><"col-5 col-sm-8 text-right"<"datatable-filter"<"d-flex justify-content-end g-2"' + btn + 'l>>>><"my-3"t><"row align-items-center"<"col-7 col-sm-12 col-md-9"p><"col-5 col-sm-12 col-md-3 text-left text-md-right"i>>';
        var dom = $(this).hasClass('is-separate') ? dom_separate : dom_normal;
        var def = {
          ajax: {
            url: data,
            type: 'POST',
          },
          responsive: true,
          autoWidth: false,
          dom: dom,
          language: {
            search: "",
            searchPlaceholder: "Type in to Search",
            lengthMenu: "<span class='d-none d-sm-inline-block'>Show</span><div class='form-control-select'> _MENU_ </div>",
            info: "_START_ -_END_ of _TOTAL_",
            infoEmpty: "No records found",
            infoFiltered: "( Total _MAX_  )",
            paginate: {
              "first": "First",
              "last": "Last",
              "next": "Next",
              "previous": "Prev"
            }
          }
        },
          attr = opt ? extend(def, opt) : def;
        attr = auto_responsive === false ? extend(attr, {
          responsive: false
        }) : attr;
        $(this).DataTable(attr);
        $('.dt-export-title').text(export_title);
      });
    }
  }; // DataTable Init @v1.0


  NioApp.DataTable.init = function () {
    NioApp.DataTable('.datatable-init', {
      responsive: {
        details: true
      }
    });

    NioApp.DataTable('.datatable-init-export', {
      responsive: {
        details: true
      },
      columnDefs: [
        { 'visible': false, 'targets': [0, 2, 3, 5, 7, 8, 11, 12, 13] }
      ],
      buttons: ['copy', 'excel']
    });

    NioApp.DataTable('.tableDashboard', {
      responsive: {
        details: true
      },
      columnDefs: [
        { 'visible': false, 'targets': [3, 6, 7, 8, 9, 10, 12, 13] }
      ],
      buttons: ['copy', 'excel']
    });

    NioApp.DataTable('.table_documents_eapp', {
      responsive: {
        details: true
      },
      lengthChange: false, bFilter: false, bInfo: false, paging: false,
    });

    NioApp.DataTable('.hrd-table', {
      responsive: {
        details: true
      },
      columnDefs: [
        { 'visible': false, 'targets': [3, 5, 6, 7, 8, 9, 12, 13, 14, 15] }
      ],
      buttons: ['copy', 'excel']
    });

    NioApp.DataTable('#table_lpd_detail', {
      responsive: {
        details: true
      },
      // order: [[ 0, "desc" ]],
      columnDefs: [
        // { 'visible': false, 'targets': [0] }
      ],
      lengthChange: false, bFilter: false, bInfo: false, paging: false,
    });

    NioApp.DataTable('.employee-table', {
      responsive: {
        details: true
      },
      buttons: ['copy', 'excel']
    });

    NioApp.DataTable('.dashboard_employee-table', {
      responsive: {
        details: true
      },
      order: [0, 'desc']
    });

    NioApp.DataTable('.family-table', {
      responsive: {
        details: true
      },
      buttons: ['copy', 'excel']
    });

    NioApp.DataTable('.pagu_rawat_jalan-table', {
      responsive: {
        details: true
      },
      buttons: ['copy', 'excel']
    });

    NioApp.DataTable('.pagu_rawat_inap-table', {
      responsive: {
        details: true
      },
      buttons: ['copy', 'excel']
    });

    NioApp.DataTable('.pagu_maternity-table', {
      responsive: {
        details: true
      },
      buttons: ['copy', 'excel']
    });

    NioApp.DataTable('.pagu_kacamata-table', {
      responsive: {
        details: true
      },
      buttons: ['copy', 'excel']
    });

    NioApp.DataTable('.grandparent-table', {
      responsive: {
        details: true
      },
      buttons: ['copy', 'excel']
    });

    NioApp.DataTable('.parent-table', {
      responsive: {
        details: true
      },
      buttons: ['copy', 'excel']
    });

    NioApp.DataTable('.child-table', {
      responsive: {
        details: true
      },
      buttons: ['copy', 'excel']
    });

    NioApp.DataTable('.e_kuitansi-table', {
      responsive: {
        details: true
      },
      order: [1, 'DESC']
    });

    NioApp.DataTable('.ToR-table', {
      responsive: {
        details: true
      },
      buttons: ['copy', 'excel']

    });

    NioApp.DataTable('.users-table', {
      responsive: {
        details: true
      },
      buttons: ['copy', 'excel']
    });

    NioApp.DataTable('.m-table', {
      responsive: {
        details: true
      },
      columnDefs: [
        { 'visible': false, 'targets': [] }
      ],
      order: [[4, "desc"]],
      buttons: ['copy', 'excel']
    });

    $.fn.DataTable.ext.pager.numbers_length = 7;
  }; // BootStrap Extended


  NioApp.BS.ddfix = function (elm, exc) {
    var dd = elm ? elm : '.dropdown-menu',
      ex = exc ? exc : 'a:not(.clickable), button:not(.clickable), a:not(.clickable) *, button:not(.clickable) *';
    $(dd).on('click', function (e) {
      if (!$(e.target).is(ex)) {
        e.stopPropagation();
        return;
      }
    });

    if (NioApp.State.isRTL) {
      var $dMenu = $('.dropdown-menu');
      $dMenu.each(function () {
        var $self = $(this);

        if ($self.hasClass('dropdown-menu-right') && !$self.hasClass('dropdown-menu-center')) {
          $self.prev('[data-toggle="dropdown"]').dropdown({
            popperConfig: {
              placement: 'bottom-start'
            }
          });
        } else if (!$self.hasClass('dropdown-menu-right') && !$self.hasClass('dropdown-menu-center')) {
          $self.prev('[data-toggle="dropdown"]').dropdown({
            popperConfig: {
              placement: 'bottom-end'
            }
          });
        }
      });
    }
  }; // BootStrap Specific Tab Open


  NioApp.BS.tabfix = function (elm) {
    var tab = elm ? elm : '[data-toggle="modal"]';
    $(tab).on('click', function () {
      var _this = $(this),
        target = _this.data('target'),
        target_href = _this.attr('href'),
        tg_tab = _this.data('tab-target');

      var modal = target ? $body.find(target) : $body.find(target_href);

      if (tg_tab && tg_tab !== '#' && modal) {
        modal.find('[href="' + tg_tab + '"]').tab('show');
      } else if (modal) {
        var tabdef = modal.find('.nk-nav.nav-tabs');
        var link = $(tabdef[0]).find('[data-toggle="tab"]');
        $(link[0]).tab('show');
      }
    });
  }; // Dark Mode Switch @since v2.0


  NioApp.ModeSwitch = function () {
    var toggle = $('.dark-switch');

    if ($body.hasClass('dark-mode')) {
      toggle.addClass('active');
    } else {
      toggle.removeClass('active');
    }

    toggle.on('click', function (e) {
      e.preventDefault();
      $(this).toggleClass('active');
      $body.toggleClass('dark-mode');
    });
  }; // Knob @v1.0


  NioApp.Knob = function (elm, opt) {
    if ($(elm).exists() && typeof $.fn.knob === 'function') {
      var def = {
        min: 0
      },
        attr = opt ? extend(def, opt) : def;
      $(elm).each(function () {
        $(this).knob(attr);
      });
    }
  }; // Knob Init @v1.0


  NioApp.Knob.init = function () {
    var knob = {
      "default": {
        readOnly: true,
        lineCap: "round"
      },
      half: {
        angleOffset: -90,
        angleArc: 180,
        readOnly: true,
        lineCap: "round"
      }
    };
    NioApp.Knob('.knob', knob["default"]);
    NioApp.Knob('.knob-half', knob.half);
  }; // Range @v1.0.1


  NioApp.Range = function (elm, opt) {
    if ($(elm).exists() && typeof noUiSlider !== 'undefined') {
      $(elm).each(function () {
        var $self = $(this),
          self_id = $self.attr('id');

        var _start = $self.data('start'),
          _start = /\s/g.test(_start) ? _start.split(' ') : _start,
          _start = _start ? _start : 0,
          _connect = $self.data('connect'),
          _connect = /\s/g.test(_connect) ? _connect.split(' ') : _connect,
          _connect = typeof _connect == 'undefined' ? 'lower' : _connect,
          _min = $self.data('min'),
          _min = _min ? _min : 0,
          _max = $self.data('max'),
          _max = _max ? _max : 100,
          _min_distance = $self.data('min-distance'),
          _min_distance = _min_distance ? _min_distance : null,
          _max_distance = $self.data('max-distance'),
          _max_distance = _max_distance ? _max_distance : null,
          _step = $self.data('step'),
          _step = _step ? _step : 1,
          _orientation = $self.data('orientation'),
          _orientation = _orientation ? _orientation : 'horizontal',
          _tooltip = $self.data('tooltip'),
          _tooltip = _tooltip ? _tooltip : false;

        console.log(_tooltip);
        var target = document.getElementById(self_id);
        var def = {
          start: _start,
          connect: _connect,
          direction: NioApp.State.isRTL ? "rtl" : "ltr",
          range: {
            'min': _min,
            'max': _max
          },
          margin: _min_distance,
          limit: _max_distance,
          step: _step,
          orientation: _orientation,
          tooltips: _tooltip
        },
          attr = opt ? extend(def, opt) : def;
        noUiSlider.create(target, attr);
      });
    }
  }; // Range Init @v1.0


  NioApp.Range.init = function () {
    NioApp.Range('.form-control-slider');
    NioApp.Range('.form-range-slider');
  };

  NioApp.Select2.init = function () {
    // NioApp.Select2('.select');
    NioApp.Select2('.form-select');
  }; // Slick Slider @v1.0.1


  NioApp.Slick = function (elm, opt) {
    if ($(elm).exists() && typeof $.fn.slick === 'function') {
      $(elm).each(function () {
        var def = {
          'prevArrow': '<div class="slick-arrow-prev"><a href="javascript:void(0);" class="slick-prev"><em class="icon ni ni-chevron-left"></em></a></div>',
          'nextArrow': '<div class="slick-arrow-next"><a href="javascript:void(0);" class="slick-next"><em class="icon ni ni-chevron-right"></em></a></div>',
          rtl: NioApp.State.isRTL
        },
          attr = opt ? extend(def, opt) : def;
        $(this).slick(attr);
      });
    }
  }; // Slick Init @v1.0


  NioApp.Slider.init = function () {
    NioApp.Slick('.slider-init');
  }; // Magnific Popup @v1.0.0


  NioApp.Lightbox = function (elm, type, opt) {
    if ($(elm).exists()) {
      $(elm).each(function () {
        var def = {};

        if (type == 'video' || type == 'iframe') {
          def = {
            type: 'iframe',
            removalDelay: 160,
            preloader: true,
            fixedContentPos: false,
            callbacks: {
              beforeOpen: function beforeOpen() {
                this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
                this.st.mainClass = this.st.el.attr('data-effect');
              }
            }
          };
        } else if (type == 'content') {
          def = {
            type: 'inline',
            preloader: true,
            removalDelay: 400,
            mainClass: 'mfp-fade content-popup'
          };
        } else {
          def = {
            type: 'image',
            mainClass: 'mfp-fade image-popup'
          };
        }

        var attr = opt ? extend(def, opt) : def;
        $(this).magnificPopup(attr);
      });
    }
  }; // Controls @v1.0.0


  NioApp.Control = function (elm) {
    var control = document.querySelectorAll(elm);
    control.forEach(function (item, index, arr) {
      item.checked ? item.parentNode.classList.add('checked') : null;
      item.addEventListener("change", function () {
        if (item.type == "checkbox") {
          item.checked ? item.parentNode.classList.add('checked') : item.parentNode.classList.remove('checked');
        }

        if (item.type == "radio") {
          document.querySelectorAll('input[name="' + item.name + '"]').forEach(function (item, index, arr) {
            item.parentNode.classList.remove('checked');
          });
          item.checked ? item.parentNode.classList.add('checked') : null;
        }
      });
    });
  }; // Number Spinner @v1.0


  NioApp.NumberSpinner = function (elm, opt) {
    var plus = document.querySelectorAll("[data-number='plus']");
    var minus = document.querySelectorAll("[data-number='minus']");
    plus.forEach(function (item, index, arr) {
      var parent = plus[index].parentNode;
      plus[index].addEventListener("click", function () {
        var child = plus[index].parentNode.children;
        child.forEach(function (item, index, arr) {
          if (child[index].classList.contains("number-spinner")) {
            var value = !child[index].value == "" ? parseInt(child[index].value) : 0;
            var step = !child[index].step == "" ? parseInt(child[index].step) : 1;
            var max = !child[index].max == "" ? parseInt(child[index].max) : Infinity;

            if (max + 1 > value + step) {
              child[index].value = value + step;
            } else {
              child[index].value = value;
            }
          }
        });
      });
    });
    minus.forEach(function (item, index, arr) {
      var parent = minus[index].parentNode;
      minus[index].addEventListener("click", function () {
        var child = minus[index].parentNode.children;
        child.forEach(function (item, index, arr) {
          if (child[index].classList.contains("number-spinner")) {
            var value = !child[index].value == "" ? parseInt(child[index].value) : 0;
            var step = !child[index].step == "" ? parseInt(child[index].step) : 1;
            var min = !child[index].min == "" ? parseInt(child[index].min) : 0;

            if (min - 1 < value - step) {
              child[index].value = value - step;
            } else {
              child[index].value = value;
            }
          }
        });
      });
    });
  }; // Extra @v1.1


  NioApp.OtherInit = function () {
    NioApp.ClassBody();
    NioApp.PassSwitch();
    NioApp.CurrentLink();
    NioApp.LinkOff('.is-disable');
    NioApp.ClassNavMenu();
    NioApp.SetHW('[data-height]', 'height');
    NioApp.SetHW('[data-width]', 'width');
    NioApp.NumberSpinner();
    NioApp.Lightbox('.popup-video', 'video');
    NioApp.Lightbox('.popup-iframe', 'iframe');
    NioApp.Lightbox('.popup-image', 'image');
    NioApp.Lightbox('.popup-content', 'content');
    NioApp.Control('.custom-control-input');
  }; // Animate Init @v1.0


  NioApp.Ani.init = function () {
    NioApp.Ani.formElm('.form-control-outlined');
    NioApp.Ani.formSearch('.toggle-search');
  }; // BootstrapExtend Init @v1.0


  NioApp.BS.init = function () {
    NioApp.BS.menutip('a.nk-menu-link');
    NioApp.BS.tooltip('.nk-tooltip');
    NioApp.BS.tooltip('.btn-tooltip', {
      placement: 'top'
    });
    NioApp.BS.tooltip('[data-toggle="tooltip"]');
    NioApp.BS.tooltip('.tipinfo,.nk-menu-tooltip', {
      placement: 'right'
    });
    NioApp.BS.popover('[data-toggle="popover"]');
    NioApp.BS.progress('[data-progress]');
    NioApp.BS.fileinput('.custom-file-input');
    NioApp.BS.modalfix();
    NioApp.BS.ddfix();
    NioApp.BS.tabfix();
  }; // Picker Init @v1.0


  NioApp.Picker.init = function () {
    NioApp.Picker.date('.date-picker');
    NioApp.Picker.dob('.date-picker-alt');
    NioApp.Picker.time('.time-picker');
    NioApp.Picker.date('.date-picker-range', {
      todayHighlight: false,
      autoclose: false
    });
  }; // Addons @v1


  NioApp.Addons.Init = function () {
    NioApp.Knob.init();
    NioApp.Range.init();
    NioApp.Select2.init();
    NioApp.Dropzone.init();
    NioApp.Slider.init();
    NioApp.DataTable.init();
  }; // Toggler @v1


  NioApp.TGL.init = function () {
    NioApp.TGL.content('.toggle');
    NioApp.TGL.expand('.toggle-expand');
    NioApp.TGL.expand('.toggle-opt', {
      toggle: false
    });
    NioApp.TGL.showmenu('.nk-nav-toggle');
    NioApp.TGL.ddmenu('.' + _menu + '-toggle', {
      self: _menu + '-toggle',
      child: _menu + '-sub'
    });
  };

  NioApp.BS.modalOnInit = function () {
    $('.modal').on('shown.bs.modal', function () {
      NioApp.Select2.init();
      NioApp.Validate.init();
    });
  }; // Initial by default
  /////////////////////////////


  NioApp.init = function () {
    NioApp.coms.docReady.push(NioApp.OtherInit);
    NioApp.coms.docReady.push(NioApp.Prettify);
    NioApp.coms.docReady.push(NioApp.ColorBG);
    NioApp.coms.docReady.push(NioApp.ColorTXT);
    NioApp.coms.docReady.push(NioApp.Copied);
    NioApp.coms.docReady.push(NioApp.Ani.init);
    NioApp.coms.docReady.push(NioApp.TGL.init);
    NioApp.coms.docReady.push(NioApp.BS.init);
    NioApp.coms.docReady.push(NioApp.Validate.init);
    NioApp.coms.docReady.push(NioApp.Picker.init);
    NioApp.coms.docReady.push(NioApp.Addons.Init);
    NioApp.coms.docReady.push(NioApp.Wizard);
    NioApp.coms.winLoad.push(NioApp.ModeSwitch);
  };

  NioApp.init();
  return NioApp;
}(NioApp, jQuery);

// $('#btn_refresh').click(function() {
//   $('#example').DataTable().ajax.reload();
// });

//////////////////////////////////////Start HRIS////////////////////////////////////////////////

$(document).ready(function () {

  $('.sub-menu_2').hide();
  $('.menu-item_2 > a').click(function (e) {
    e.preventDefault();
    $(this).next('.sub-menu_2').slideToggle();
  });

  $('input.check11').click(function () {
    $('input.check22').prop('checked', this.checked)
  })
});

/////////////////////////////////////////Login HRIS//////////////////////////////////////////

$('#sign_in').click(function () {
  var email = $("#email-address").val();
  var password = $("#password").val();
});

//////////////////////////////////// Master Pagu Medical ////////////////////////////////////////////

$('.tambah_pagu_rawat_jalan').click(function () {

  var start_date = $("#start_date_tambah_rawat_jalan").val();
  var end_date = $("#end_date_tambah_rawat_jalan").val();
  var grade = $("#grade_tambah_rawat_jalan").val();
  var pagu_tahun = $("#pagu_tahun_tambah_rawat_jalan").val();
  var url = "master/tambah_pagu_rawat_jalan";
  //alert(start_date);
  if ((start_date == "") || (start_date === undefined) || (end_date == "") || (end_date === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Periode tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((pagu_tahun == "") || (pagu_tahun === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Pagu Tahunan tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  }

  $.ajax({
    url: url,
    dataType: 'json',
    type: 'POST',
    data: { start_date: start_date, end_date: end_date, grade: grade, pagu_tahun: pagu_tahun },
    success: function (data) {
      if (data == true) {
        $('#table_pagu_rawat_jalan').DataTable().ajax.reload();
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Data telah ditambahkan',
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (data) {
      $('#table_pagu_rawat_jalan').DataTable().ajax.reload();
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Data tidak dapat ditambahkan',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });
});


$('.tambah_pagu_rawat_inap').click(function () {

  var start_date = $("#start_date_tambah_rawat_inap").val();
  var end_date = $("#end_date_tambah_rawat_inap").val();
  var grade = $("#grade_tambah_rawat_inap").val();
  var pagu_kamar = $("#pagu_kamar_tambah_rawat_inap").val();
  var pagu_tahun = $("#pagu_tahun_tambah_rawat_inap").val();
  var url = "master/tambah_pagu_rawat_inap";

  if ((start_date == "") || (start_date === undefined) || (end_date == "") || (end_date === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Periode tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((pagu_kamar == "") || (pagu_kamar === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Pagu Kamar tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((pagu_tahun == "") || (pagu_tahun === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Pagu Tahunan tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  }

  $.ajax({
    url: url,
    dataType: 'json',
    type: 'POST',
    data: { start_date: start_date, end_date: end_date, grade: grade, pagu_kamar: pagu_kamar, pagu_tahun: pagu_tahun },
    success: function (data) {
      if (data == true) {
        $('#table_pagu_rawat_inap').DataTable().ajax.reload();
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Data telah ditambahkan',
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (data) {
      $('#table_pagu_rawat_inap').DataTable().ajax.reload();
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Data tidak dapat ditambahkan',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });
});

$('.tambah_pagu_maternity').click(function () {

  var start_date = $("#start_date_tambah_maternity").val();
  var end_date = $("#end_date_tambah_maternity").val();
  var melahirkan = $("#melahirkan_tambah_maternity").val();
  var grade = $("#grade_tambah_maternity").val();
  var pagu_tahun = $("#pagu_tahun_tambah_maternity").val();
  var url = "master/tambah_pagu_maternity";

  if ((start_date == "") || (start_date === undefined) || (end_date == "") || (end_date === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Periode tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((melahirkan == "") || (melahirkan === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Jenis melahirkan tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((pagu_tahun == "") || (pagu_tahun === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Pagu Tahunan tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  }

  $.ajax({
    url: url,
    dataType: 'json',
    type: 'POST',
    data: { start_date: start_date, end_date: end_date, melahirkan: melahirkan, grade: grade, pagu_tahun: pagu_tahun },
    success: function (data) {
      if (data == true) {
        $('#table_pagu_maternity').DataTable().ajax.reload();
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Data telah ditambahkan',
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (data) {
      $('#table_pagu_maternity').DataTable().ajax.reload();
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Data tidak dapat ditambahkan',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });
});


$('.tambah_pagu_kacamata').click(function () {
  var start_date = $("#start_date_tambah_kacamata").val();
  var end_date = $("#end_date_tambah_kacamata").val();
  var grade = $("#grade_tambah_kacamata").val();
  var pagu_one_focus = $("#pagu_lensa_one_focus_tambah_kacamata").val();
  var pagu_two_focus = $("#pagu_lensa_two_focus_tambah_kacamata").val();
  var pagu_frame = $("#pagu_frame_tambah_kacamata").val();
  var url = "master/tambah_pagu_kacamata";

  if ((start_date == "") || (start_date === undefined) || (end_date == "") || (end_date === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Periode tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((pagu_one_focus == "") || (pagu_one_focus === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Pagu One Focus tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((pagu_two_focus == "") || (pagu_two_focus === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Pagu Two Focus tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((pagu_frame == "") || (pagu_frame === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Pagu Frame tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  }

  $.ajax({
    url: url,
    dataType: 'json',
    type: 'POST',
    data: { start_date: start_date, end_date: end_date, grade: grade, pagu_one_focus: pagu_one_focus, pagu_two_focus: pagu_two_focus, pagu_frame: pagu_frame },
    success: function (data) {
      if (data == true) {
        $('#table_pagu_kacamata').DataTable().ajax.reload();
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Data telah ditambahkan',
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (data) {
      $('#table_pagu_kacamata').DataTable().ajax.reload();
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Data tidak dapat ditambahkan',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });


});

function edit_pagu_rawat_jalan(edit_rawat_jalan) {
  var id = document.getElementById(edit_rawat_jalan).getAttribute("id");
  var url = 'master/getEditPaguRawatJalan/';
  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'json',
    data: { id: id },
    success: function (response) {
      //$('.data_edit_pagu_rawat_jalan').modal('show');
      var id = response[0].id;
      var start_date = response[0].start_date;
      var end_date = response[0].end_date;
      var grade = response[0].grade;
      var pagu_tahun = response[0].pagu_tahun;

      $("#id_edit_rawat_jalan").val(id);
      $("#start_date_edit_rawat_jalan").val(start_date);
      $("#end_date_edit_rawat_jalan").val(end_date);
      $("#grade_edit_rawat_jalan").val(grade).change();
      $("#pagu_tahun_edit_rawat_jalan").val(pagu_tahun);
    }
  });
}

$('.ubah_pagu_rawat_jalan').click(function () {

  var id = $("#id_edit_rawat_jalan").val();
  var start_date = $("#start_date_edit_rawat_jalan").val();
  var end_date = $("#end_date_edit_rawat_jalan").val();
  var grade = $("#grade_edit_rawat_jalan").val();
  var pagu_tahun = $("#pagu_tahun_edit_rawat_jalan").val();
  var url = "master/ubah_pagu_rawat_jalan";

  if ((start_date == "") || (start_date === undefined) || (end_date == "") || (end_date === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Periode tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((pagu_tahun == "") || (pagu_tahun === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Pagu Tahunan tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  }

  $.ajax({
    url: url,
    dataType: 'json',
    type: 'POST',
    data: { id: id, start_date: start_date, end_date: end_date, grade: grade, pagu_tahun: pagu_tahun },
    success: function (data) {
      if (data == true) {
        $('#table_pagu_rawat_jalan').DataTable().ajax.reload();
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Data telah ditambahkan',
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (data) {
      $('#table_pagu_rawat_jalan').DataTable().ajax.reload();
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Data tidak dapat ditambahkan',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });
});

function delete_pagu_rawat_jalan(DeletePaguRawatJalan) {
  var id = document.getElementById(DeletePaguRawatJalan).getAttribute("id");
  var url = 'master/getDeletePaguRawatJalan/';
  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'json',
    data: { id: id },
    success: function (data) {
      if (data == true) {
        $('#table_pagu_rawat_jalan').DataTable().ajax.reload();
        Swal.fire({
          position: 'center',
          icon: 'info',
          title: 'Data telah dihapus',
          showConfirmButton: false,
          timer: 1500
        });
      } else {
        Swal.fire({
          position: 'center',
          icon: 'error',
          title: 'Data tidak dapat dihapus',
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (data) {
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Data tidak dapat dihapus',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });
}

function edit_pagu_rawat_inap(edit_rawat_inap) {
  var id = document.getElementById(edit_rawat_inap).getAttribute("id");
  var url = 'master/getEditPaguRawatInap/';
  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'json',
    data: { id: id },
    success: function (response) {
      //$('.data_edit_pagu_rawat_inap').modal('show');
      var id = response[0].id;
      var start_date = response[0].start_date;
      var end_date = response[0].end_date;
      var grade = response[0].grade;
      var pagu_tahun = response[0].pagu_tahun;
      var pagu_kamar_hari = response[0].pagu_kamar_hari;

      $("#id_edit_rawat_inap").val(id);
      $("#start_date_edit_rawat_inap").val(start_date);
      $("#end_date_edit_rawat_inap").val(end_date);
      $("#grade_edit_rawat_inap").val(grade).change();
      $("#pagu_kamar_edit_rawat_inap").val(pagu_kamar_hari);
      $("#pagu_tahun_edit_rawat_inap").val(pagu_tahun);
    }
  });
}

$('.ubah_pagu_rawat_inap').click(function () {

  var id = $("#id_edit_rawat_inap").val();
  var start_date = $("#start_date_edit_rawat_inap").val();
  var end_date = $("#end_date_edit_rawat_inap").val();
  var grade = $("#grade_edit_rawat_inap").val();
  var pagu_kamar = $("#pagu_kamar_edit_rawat_inap").val();
  var pagu_tahun = $("#pagu_tahun_edit_rawat_inap").val();
  var url = "master/ubah_pagu_rawat_inap";

  if ((start_date == "") || (start_date === undefined) || (end_date == "") || (end_date === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Periode tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((pagu_kamar == "") || (pagu_kamar === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Pagu Kamar tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((pagu_tahun == "") || (pagu_tahun === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Pagu Tahunan tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  }

  $.ajax({
    url: url,
    dataType: 'json',
    type: 'POST',
    data: { id: id, start_date: start_date, end_date: end_date, grade: grade, pagu_kamar: pagu_kamar, pagu_tahun: pagu_tahun },
    success: function (data) {
      if (data == true) {
        $('#table_pagu_rawat_inap').DataTable().ajax.reload();
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Data telah ditambahkan',
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (data) {
      $('#table_pagu_rawat_inap').DataTable().ajax.reload();
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Data tidak dapat ditambahkan',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });
});

function delete_pagu_rawat_inap(DeletePaguRawatInap) {
  var id = document.getElementById(DeletePaguRawatInap).getAttribute("id");
  var url = 'master/getDeletePaguRawatInap/';
  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'json',
    data: { id: id },
    success: function (data) {
      if (data == true) {
        $('#table_pagu_rawat_inap').DataTable().ajax.reload();
        Swal.fire({
          position: 'center',
          icon: 'info',
          title: 'Data telah dihapus',
          showConfirmButton: false,
          timer: 1500
        });
      } else {
        Swal.fire({
          position: 'center',
          icon: 'error',
          title: 'Data tidak dapat dihapus',
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (data) {
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Data tidak dapat dihapus',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });
}


function edit_pagu_maternity(edit_maternity) {
  var id = document.getElementById(edit_maternity).getAttribute("id");
  var url = 'master/getEditPaguMaternity/';
  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'json',
    data: { id: id },
    success: function (response) {
      //$('.data_edit_pagu_rawat_inap').modal('show');
      var id = response[0].id;
      var start_date = response[0].start_date;
      var end_date = response[0].end_date;
      var melahirkan = response[0].melahirkan;
      var grade = response[0].grade;
      var pagu_tahun = response[0].pagu_tahun;
      $("#id_edit_maternity").val(id);
      $("#start_date_edit_maternity").val(start_date);
      $("#end_date_edit_maternity").val(end_date);
      $("#melahirkan_edit_maternity").val(melahirkan).change();
      $("#grade_edit_maternity").val(grade).change();
      $("#pagu_tahun_edit_maternity").val(pagu_tahun);
    }
  });
}

$('.ubah_pagu_maternity').click(function () {

  var id = $("#id_edit_maternity").val();
  var start_date = $("#start_date_edit_maternity").val();
  var end_date = $("#end_date_edit_maternity").val();
  var melahirkan = $("#grade_edit_melahirkan").val();
  var grade = $("#grade_edit_maternity").val();
  var pagu_tahun = $("#pagu_tahun_edit_maternity").val();
  var url = "master/ubah_pagu_maternity";

  if ((start_date == "") || (start_date === undefined) || (end_date == "") || (end_date === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Periode tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((melahirkan == "") || (melahirkan === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Jenis Melahirkan tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((pagu_tahun == "") || (pagu_tahun === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Pagu Tahunan tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  }

  $.ajax({
    url: url,
    dataType: 'json',
    type: 'POST',
    data: { id: id, start_date: start_date, end_date: end_date, melahirkan: melahirkan, grade: grade, pagu_kamar: pagu_kamar, pagu_tahun: pagu_tahun },
    success: function (data) {
      if (data == true) {
        $('#table_pagu_maternity').DataTable().ajax.reload();
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Data telah ditambahkan',
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (data) {
      $('#table_pagu_maternity').DataTable().ajax.reload();
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Data tidak dapat ditambahkan',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });
});

function delete_pagu_maternity(DeletePaguMaternity) {
  var id = document.getElementById(DeletePaguMaternity).getAttribute("id");
  var url = 'master/getDeletePaguMaternity/';
  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'json',
    data: { id: id },
    success: function (data) {
      if (data == true) {
        $('#table_pagu_maternity').DataTable().ajax.reload();
        Swal.fire({
          position: 'center',
          icon: 'info',
          title: 'Data telah dihapus',
          showConfirmButton: false,
          timer: 1500
        });
      } else {
        Swal.fire({
          position: 'center',
          icon: 'error',
          title: 'Data tidak dapat dihapus',
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (data) {
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Data tidak dapat dihapus',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });
}


function edit_pagu_kacamata(edit_kacamata) {
  var id = document.getElementById(edit_kacamata).getAttribute("id");
  var url = 'master/getEditPaguKacamata/';
  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'json',
    data: { id: id },
    success: function (response) {
      //$('.data_edit_pagu_kacamata').modal('show');
      var id = response[0].id;
      var start_date = response[0].start_date;
      var end_date = response[0].end_date;
      var grade = response[0].grade;
      var pagu_frame_dua_tahun = response[0].pagu_frame_dua_tahun;
      var pagu_one_focus = response[0].pagu_one_focus_tahun;
      var pagu_two_focus = response[0].pagu_two_focus_tahun;

      $("#id_edit_kacamata").val(id);
      $("#start_date_edit_kacamata").val(start_date);
      $("#end_date_edit_kacamata").val(end_date);
      $("#grade_edit_kacamata").val(grade).change();
      $("#pagu_lensa_one_focus_edit_kacamata").val(pagu_one_focus);
      $("#pagu_lensa_two_focus_edit_kacamata").val(pagu_two_focus);
      $("#pagu_frame_edit_kacamata").val(pagu_frame_dua_tahun);
    }
  });
}

$('.ubah_pagu_kacamata').click(function () {

  var id = $("#id_edit_kacamata").val();
  var start_date = $("#start_date_edit_kacamata").val();
  var end_date = $("#end_date_edit_kacamata").val();
  var grade = $("#grade_edit_kacamata").val();
  var pagu_one_focus = $("#pagu_lensa_one_focus_edit_kacamata").val();
  var pagu_two_focus = $("#pagu_lensa_two_focus_edit_kacamata").val();
  var pagu_frame = $("#pagu_frame_edit_kacamata").val();
  var url = "master/ubah_pagu_kacamata";

  if ((start_date == "") || (start_date === undefined) || (end_date == "") || (end_date === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Periode tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((pagu_one_focus == "") || (pagu_one_focus === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Pagu One Focus tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((pagu_two_focus == "") || (pagu_two_focus === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Pagu Two Focus tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((pagu_frame == "") || (pagu_frame === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Pagu Frame tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  }

  $.ajax({
    url: url,
    dataType: 'json',
    type: 'POST',
    data: { id: id, start_date: start_date, end_date: end_date, grade: grade, pagu_one_focus: pagu_one_focus, pagu_two_focus: pagu_two_focus, pagu_frame: pagu_frame },
    success: function (data) {
      if (data == true) {
        $('#table_pagu_kacamata').DataTable().ajax.reload();
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Data telah ditambahkan',
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (data) {
      $('#table_pagu_kacamata').DataTable().ajax.reload();
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Data tidak dapat ditambahkan',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });


});

function delete_pagu_kacamata(DeletePaguKacamata) {
  var id = document.getElementById(DeletePaguKacamata).getAttribute("id");
  var url = 'master/getDeletePaguKacamata/';
  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'json',
    data: { id: id },
    success: function (data) {
      if (data == true) {
        $('#table_pagu_kacamata').DataTable().ajax.reload();
        Swal.fire({
          position: 'center',
          icon: 'info',
          title: 'Data telah dihapus',
          showConfirmButton: false,
          timer: 1500
        });
      } else {
        Swal.fire({
          position: 'center',
          icon: 'error',
          title: 'Data tidak dapat dihapus',
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (data) {
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Data tidak dapat dihapus',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });
}


//////////////////////////////////// Master Type of Reimbursment ////////////////////////////////////////////

$(document).ready(function () {

  var get_Grandparent = "master/get_Grandparent";
  $.ajax({
    url: get_Grandparent,
    dataType: 'json',
    type: 'POST',
    data: {},
    success: function (data) {
      $('#parent_grandparent_tambah').html(data);
      $('#child_grandparent_tambah').html(data);
      $('#request_grandparent_tambah').html(data);
    }
  });
});

$('.tambah_grandparent').click(function () {

  var grandparent_tambah = $("#grandparent_tambah").val();
  var description_grandparent_tambah = $("#description_grandparent_tambah").val();
  var url = "master/tambah_grandparent";

  if ((grandparent_tambah == "") || (grandparent_tambah === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Jenis Penggantian tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  }

  $.ajax({
    url: url,
    dataType: 'json',
    type: 'POST',
    data: { grandparent_tambah: grandparent_tambah, description_grandparent_tambah: description_grandparent_tambah },
    success: function (data) {
      if (data == true) {
        $('#table_grandparent').DataTable().ajax.reload();
        var url = "master/get_Grandparent";
        var id = "";
        $.ajax({
          url: url,
          dataType: 'json',
          type: 'POST',
          data: { id: id },
          success: function (data) {
            $('#parent_grandparent_tambah').html(data);
            $('#child_grandparent_tambah').html(data);
          }
        });

        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Data telah ditambahkan',
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (data) {
      $('#table_grandparent').DataTable().ajax.reload();
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Data tidak dapat ditambahkan',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });

});


$('.tambah_parent').click(function () {

  var parent_grandparent_tambah = $("#parent_grandparent_tambah").val();
  var parent_tambah = $("#parent_tambah").val();
  var description_parent_tambah = $("#description_parent_tambah").val();
  var url = "master/tambah_parent";

  if ((parent_grandparent_tambah == "") || (parent_grandparent_tambah === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Jenis Penggantian tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((parent_tambah == "") || (parent_tambah === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Sub Penggantian tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  }

  $.ajax({
    url: url,
    dataType: 'json',
    type: 'POST',
    data: { parent_grandparent_tambah: parent_grandparent_tambah, parent_tambah: parent_tambah, description_parent_tambah: description_parent_tambah },
    success: function (data) {
      if (data == true) {
        $('#table_parent').DataTable().ajax.reload();

        var url = "master/get_Parent";
        var id = "";
        $.ajax({
          url: url,
          dataType: 'json',
          type: 'POST',
          data: { id: id },
          success: function (data) {
            $('#child_parent_tambah').html(data);
          }
        });
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Data telah ditambahkan',
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (data) {
      $('#table_parent').DataTable().ajax.reload();
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Data tidak dapat ditambahkan',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });
});

$('.tambah_child').click(function () {

  var start_date = $("#start_date_tambah_child").val();
  var end_date = $("#end_date_tambah_child").val();
  var child_grandparent_tambah = $("#child_grandparent_tambah").val();
  var child_parent_tambah = $("#child_parent_tambah").val();
  var child_tambah = $("#child_tambah").val();
  var claim_percentage_child_tambah = $("#claim_percentage_child_tambah").val();
  var description_child_tambah = $("#description_child_tambah").val();
  var url = "master/tambah_child";

  if ((start_date == "") || (start_date === undefined) || (end_date == "") || (end_date === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Periode tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((child_grandparent_tambah == "") || (child_grandparent_tambah === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Jenis Penggantian tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((child_parent_tambah == "") || (child_parent_tambah === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Sub Penggantian tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((child_tambah == "") || (child_tambah === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Detail Penggantian tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((claim_percentage_child_tambah == "") || (claim_percentage_child_tambah === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Claim Percentage tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  }

  $.ajax({
    url: url,
    dataType: 'json',
    type: 'POST',
    data: { start_date: start_date, end_date: end_date, child_grandparent_tambah: child_grandparent_tambah, child_parent_tambah: child_parent_tambah, child_tambah: child_tambah, claim_percentage_child_tambah: claim_percentage_child_tambah, description_child_tambah: description_child_tambah },
    success: function (data) {
      if (data == true) {
        $('#table_child').DataTable().ajax.reload();
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Data telah ditambahkan',
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (data) {
      $('#table_child').DataTable().ajax.reload();
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Data tidak dapat ditambahkan',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });
});

$('#child_grandparent_tambah').change(function () {
  var grandparent = $('#child_grandparent_tambah').val();
  if (grandparent != '') {
    var get_Parent = "master/get_Parent";
    $.ajax({
      url: get_Parent,
      method: 'POST',
      data: { grandparent: grandparent },
      success: function (data) {
        $('#child_parent_tambah').html(data);
      }
    });
  }
  else {
    $('#child_parent_tambah').html('<option value="">Sub Penggantian</option>');
  }
});

$('#request_grandparent_tambah').change(function () {
  var grandparent = $('#request_grandparent_tambah').val();
  if (grandparent != '') {
    var get_Parent = "master/get_Parent";
    $.ajax({
      url: get_Parent,
      method: 'POST',
      data: { grandparent: grandparent },
      success: function (data) {
        $('#request_parent_tambah').html(data);
        $('#request_child_tambah').html('<option value="">Penggantian</option>');
      }
    });

  }
  else {
    $('#request_parent_tambah').html('<option value="">Sub Penggantian</option>');
    $('#request_child_tambah').html('<option value="">Penggantian</option>');
  }
});

$('#request_parent_tambah').change(function () {
  var parent = $('#request_parent_tambah').val();

  if (parent != '') {
    var get_Child = "master/get_Child";
    $.ajax({
      url: get_Child,
      method: 'POST',
      data: { parent: parent },
      success: function (data) {
        $('#request_child_tambah').html(data);
      }
    });
  }
  else {
    $('#request_child_tambah').html('<option value="">Penggantian</option>');
  }
});


function edit_grandparent(edit_grandparent) {
  var id = document.getElementById(edit_grandparent).getAttribute("id");
  var url = 'master/getEditGrandparent/';
  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'json',
    data: { id: id },
    success: function (response) {
      //$('.data_edit_pagu_rawat_jalan').modal('show');
      var id = response[0].id;
      var grandparent = response[0].grandparent;
      var description = response[0].description;

      $("#id_grandparent_edit").val(id);
      $("#grandparent_edit").val(grandparent);
      $("#description_grandparent_edit").val(description);
    }
  });
}

$('.ubah_grandparent').click(function () {

  var id = $("#id_grandparent_edit").val();
  var grandparent = $("#grandparent_edit").val();
  var description = $("#description_grandparent_edit").val();
  var url = "master/ubah_grandparent";

  if ((grandparent == "") || (grandparent === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Jenis Penggantian tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  }

  $.ajax({
    url: url,
    dataType: 'json',
    type: 'POST',
    data: { id: id, grandparent: grandparent, description: description },
    success: function (data) {
      if (data == true) {
        $('#table_grandparent').DataTable().ajax.reload();
        Swal.fire({
          position: 'center',
          icon: 'info',
          title: 'Data telah berhasil dirubah',
          showConfirmButton: false,
          timer: 1500
        });
      } else {
        Swal.fire({
          position: 'center',
          icon: 'error',
          title: 'Data tidak dapat dirubah',
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (data) {
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Data tidak dapat dirubah',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });
});

function delete_grandparent(delete_grandparent) {
  var id = document.getElementById(delete_grandparent).getAttribute("id");
  var url = 'master/getDeleteGrandparent/';
  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'json',
    data: { id: id },
    success: function (data) {
      if (data == true) {
        $('#table_grandparent').DataTable().ajax.reload();
        Swal.fire({
          position: 'center',
          icon: 'info',
          title: 'Data telah dihapus',
          showConfirmButton: false,
          timer: 1500
        });
      } else {
        Swal.fire({
          position: 'center',
          icon: 'error',
          title: 'Data tidak dapat dihapus',
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (data) {
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Data tidak dapat dihapus',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });
}

function edit_parent(edit_parent) {
  var id = document.getElementById(edit_parent).getAttribute("id");
  var url = 'master/getEditParent/';
  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'json',
    data: { id: id },
    success: function (response) {
      //$('.data_edit_pagu_rawat_jalan').modal('show');
      var id = response[0].id;
      var grandparent = response[0].grandparent;
      var parent = response[0].parent;
      var description = response[0].description;
      var url_edit_get = "master/edit_get_Grandparent";

      $.ajax({
        url: url_edit_get,
        dataType: 'json',
        type: 'POST',
        data: { grandparent: grandparent },
        success: function (data) {
          $("#parent_grandparent_edit").html(data);
        }
      });

      $("#id_parent_edit").val(id);
      $("#parent_edit").val(parent);
      $("#description_parent_edit").val(description);
    }
  });
}

$('.ubah_parent').click(function () {

  var id = $("#id_parent_edit").val();
  var grandparent = $("#parent_grandparent_edit").val();
  var parent = $("#parent_edit").val();
  var description = $("#description_parent_edit").val();
  var url = "master/ubah_parent";

  if ((grandparent == "") || (grandparent === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Jenis Penggantian tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((parent == "") || (parent === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Sub Penggantian tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  }

  $.ajax({
    url: url,
    dataType: 'json',
    type: 'POST',
    data: { id: id, grandparent: grandparent, parent: parent, description: description },
    success: function (data) {
      if (data == true) {
        $('#table_parent').DataTable().ajax.reload();
        Swal.fire({
          position: 'center',
          icon: 'info',
          title: 'Data telah berhasil dirubah',
          showConfirmButton: false,
          timer: 1500
        });
      } else {
        Swal.fire({
          position: 'center',
          icon: 'error',
          title: 'Data tidak dapat dirubah',
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (data) {
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Data tidak dapat dirubah',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });
});

function delete_parent(delete_parent) {
  var id = document.getElementById(delete_parent).getAttribute("id");
  var url = 'master/getDeleteParent/';
  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'json',
    data: { id: id },
    success: function (data) {
      if (data == true) {
        $('#table_parent').DataTable().ajax.reload();
        Swal.fire({
          position: 'center',
          icon: 'info',
          title: 'Data telah dihapus',
          showConfirmButton: false,
          timer: 1500
        });
      } else {
        Swal.fire({
          position: 'center',
          icon: 'error',
          title: 'Data tidak dapat dihapus',
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (data) {
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Data tidak dapat dihapus',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });
}


function edit_child(edit_child) {
  var id = document.getElementById(edit_child).getAttribute("id");
  var url = 'master/getEditChild/';
  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'json',
    data: { id: id },
    success: function (response) {
      //$('.data_edit_pagu_rawat_jalan').modal('show');
      var id = response[0].id;
      var grandparent = response[0].grandparent;
      var parent = response[0].parent;
      var child = response[0].child;
      var start_date = response[0].start_date;
      var end_date = response[0].end_date;
      var claim_percentage = response[0].claim_percentage;
      var claim_value = response[0].claim_value;
      var description = response[0].description;
      var url_edit_get_g = "master/edit_get_Grandparent";
      var url_edit_get_p = "master/edit_get_Parent";

      $.ajax({
        url: url_edit_get_g,
        dataType: 'json',
        type: 'POST',
        data: { grandparent: grandparent },
        success: function (data) {
          $("#child_grandparent_edit").html(data);
        }
      });

      $.ajax({
        url: url_edit_get_p,
        dataType: 'json',
        type: 'POST',
        data: { parent: parent },
        success: function (data) {
          $("#child_parent_edit").html(data);
        }
      });
      //alert(child);

      $("#id_child_edit").val(id);
      $("#child_edit").val(child);
      $("#start_date_edit_child").val(start_date);
      $("#end_date_edit_child").val(end_date);
      $("#claim_percentage_child_edit").val(claim_percentage);
      $("#claim_value_child_edit").val(claim_value);
      $("#description_child_edit").val(description);
    }
  });
}

$('.ubah_child').click(function () {

  var id = $("#id_child_edit").val();
  var start_date = $("#start_date_edit_child").val();
  var end_date = $("#end_date_edit_child").val();
  var child_grandparent = $("#child_grandparent_edit").val();
  var child_parent = $("#child_parent_edit").val();
  var child = $("#child_edit").val();
  var claim_percentage_child = $("#claim_percentage_child_edit").val();
  var claim_value_child = $("#claim_value_child_edit").val();
  var description_child = $("#description_child_edit").val();
  var url = "master/ubah_child";

  if ((start_date == "") || (start_date === undefined) || (end_date == "") || (end_date === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Periode tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((child_grandparent == "") || (child_grandparent === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Jenis Penggantian tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((child_parent == "") || (child_parent === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Sub Penggantian tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((child == "") || (child === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Detail Penggantian tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((claim_percentage_child == "") || (claim_percentage_child === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Claim Percentage tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  }

  $.ajax({
    url: url,
    dataType: 'json',
    type: 'POST',
    data: { id: id, start_date: start_date, end_date: end_date, child_grandparent: child_grandparent, child_parent: child_parent, child: child, claim_percentage_child: claim_percentage_child, claim_value_child: claim_value_child, description_child: description_child },
    success: function (data) {
      if (data == true) {
        $('#table_child').DataTable().ajax.reload();
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Data telah berhasil dirubah',
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (data) {
      $('#table_child').DataTable().ajax.reload();
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Data tidak berhasil dirubah',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });
});

function delete_child(delete_child) {
  var id = document.getElementById(delete_child).getAttribute("id");
  var url = 'master/getDeleteChild/';
  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'json',
    data: { id: id },
    success: function (data) {
      if (data == true) {
        $('#table_child').DataTable().ajax.reload();
        Swal.fire({
          position: 'center',
          icon: 'info',
          title: 'Data telah dihapus',
          showConfirmButton: false,
          timer: 1500
        });
      } else {
        Swal.fire({
          position: 'center',
          icon: 'error',
          title: 'Data tidak dapat dihapus',
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (data) {
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Data tidak dapat dihapus',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });
}


//////////////////////////////////////Master Couple//////////////////////////////////

$('#getCoupleEmployee').click(function () {

  $('#form_add_couple').trigger("reset");

  var getMaleEmployee = "master/getMaleEmployee";
  $.ajax({
    url: getMaleEmployee,
    dataType: 'json',
    type: 'POST',
    data: {},
    success: function (data) {
      $('#full_name_male_add_couple').html(data);
    }
  });

  var getFemaleEmployee = "master/getFemaleEmployee";
  $.ajax({
    url: getFemaleEmployee,
    dataType: 'json',
    type: 'POST',
    data: {},
    success: function (data) {
      $('#full_name_female_add_couple').html(data);
    }
  });

});

$('#full_name_male_add_couple').change(function () {
  var full_name_male_add_couple = $('#full_name_male_add_couple').val();
  if (full_name_male_add_couple != '') {
    var get_DataEmployeeMale = "master/get_DataEmployeeMale";
    $.ajax({
      url: get_DataEmployeeMale,
      method: 'POST',
      dataType: 'json',
      data: { full_name_male_add_couple: full_name_male_add_couple },
      success: function (data) {
        //alert(data)
        var nik = data.nik;
        $('#employee_id_male').val(nik);
      }
    });
  }
});

$('#full_name_female_add_couple').change(function () {
  var full_name_female_add_couple = $('#full_name_female_add_couple').val();
  if (full_name_female_add_couple != '') {
    var get_DataEmployeeFemale = "master/get_DataEmployeeFemale";
    $.ajax({
      url: get_DataEmployeeFemale,
      method: 'POST',
      dataType: 'json',
      data: { full_name_female_add_couple: full_name_female_add_couple },
      success: function (data) {
        //alert(data)
        var nik = data.nik;
        $('#employee_id_female').val(nik);
      }
    });
  }
});

$('.add_couple_employee').click(function () {

  var complete_name_male = $("#full_name_male_add_couple").val();
  var complete_name_female = $("#full_name_female_add_couple").val();
  var employee_id_male = $("#employee_id_male").val();
  var employee_id_female = $("#employee_id_female").val();

  var url = "master/add_couple_employee";

  if ((employee_id_male == "") || (employee_id_male === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Nomor Induk Karyawan tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((employee_id_female == "") || (employee_id_female === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Nomor Induk Karyawan tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  }
  $.ajax({
    url: url,
    dataType: 'json',
    type: 'POST',
    data: { employee_id_male: employee_id_male, employee_id_female: employee_id_female, complete_name_male: complete_name_male, complete_name_female: complete_name_female },
    success: function (data) {
      if (data == true) {
        $('#table_couple').DataTable().ajax.reload();
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Data telah ditambahkan',
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (data) {
      $('#table_couple').DataTable().ajax.reload();
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Data tidak dapat ditambahkan',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });
});

function act_child(act_child) {
  var id = document.getElementById(act_child).getAttribute("id");
  var isChecked = $('#' + id).prop('checked');
  var st_act = "";
  if (isChecked == true) {
    st_act = "Y";
  } else {
    st_act = "N";
  }
  //alert(st_act);

  var url = 'master/save_act_child/';
  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'json',
    data: { id: id, st_act: st_act },
    success: function (data) {
      $('#table_family').DataTable().ajax.reload();

      Swal.fire({
        position: 'center',
        icon: 'warning',
        title: 'Switched',
        showConfirmButton: false,
        timer: 1500
      });

    }
  });

}

//////////////////////////////////////Users//////////////////////////////////////////


$('#getEmployeeToUsers').click(function () {

  $('#form_tambah_users').trigger("reset");

  var getEmployeeToUsers = "master/getEmployeeToUsers";
  $.ajax({
    url: getEmployeeToUsers,
    dataType: 'json',
    type: 'POST',
    data: {},
    success: function (data) {
      $('#full_name_tambah_users').html(data);
    }
  });
});

$('#full_name_tambah_users').change(function () {
  var full_name_tambah_users = $('#full_name_tambah_users').val();
  if (full_name_tambah_users != '') {
    var get_Data_Employee = "master/get_DataEmployeeToUsers";
    $.ajax({
      url: get_Data_Employee,
      method: 'POST',
      dataType: 'json',
      data: { full_name_tambah_users: full_name_tambah_users },
      success: function (data) {
        //alert(data)
        var email = data.email;
        var nik = data.nik;
        var phone_number = data.phone_number;
        $('#employee_id_tambah').val(nik);
        $('#email_tambah').val(email);
        $('#phone_tambah').val(phone_number);
      }
    });
  }
});

$('.tambah_user').click(function () {

  var complete_name = $("#full_name_tambah_users").val();
  var nik = $("#employee_id_tambah").val();
  var email = $("#email_tambah").val();
  var phone_number = $("#phone_tambah").val();
  var password = $("#password_tambah").val();
  var role = $("#role_tambah").val();
  var access = $("#access_tambah").val();
  var verification = $("#verification_tambah").val();
  var url = "master/tambah_user";

  if ((nik == "") || (nik === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Nomor Induk Karyawan tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((password == "") || (password === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Password tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  }
  $.ajax({
    url: url,
    dataType: 'json',
    type: 'POST',
    data: { nik: nik, complete_name: complete_name, email: email, phone_number: phone_number, password: password, role: role, access: access, verification: verification },
    success: function (data) {
      if (data == true) {
        $('#table_users').DataTable().ajax.reload();
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Data telah ditambahkan',
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (data) {
      $('#table_users').DataTable().ajax.reload();
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Data tidak dapat ditambahkan',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });
});

///////////////////////////////////////////tambah item TOR Medical Reimbursement/////////////////////////////////////////

function hitungSelisihHari(tgl1, tgl2) {
  var miliday = 24 * 60 * 60 * 1000;
  var tanggal1 = new Date(tgl1);
  var tanggal2 = new Date(tgl2);
  var tglPertama = Date.parse(tanggal1);
  var tglKedua = Date.parse(tanggal2);
  var selisih = (tglKedua - tglPertama) / miliday;
  return selisih;
}

$(document).ready(function () {


  var request_id = $("#request_id").val();
  var get_sum_penggantian_jalan = "form/get_sum_penggantian_jalan";
  $.ajax({
    url: get_sum_penggantian_jalan,
    method: 'POST',
    dataType: 'json',
    data: { request_id: request_id },
    success: function (data_jalan) {
      var sum_penggantian_jalan = Intl.NumberFormat().format(data_jalan[0].sum_penggantian);
      $('#sum_penggantian_jalan').val(sum_penggantian_jalan);
      var limit_rawat_jalan = $("#limit_rawat_jalan").val();
      sum_penggantian_jalan = parseFloat(sum_penggantian_jalan.replace(/,/g, ''));
      limit_rawat_jalan = parseFloat(limit_rawat_jalan.replace(/,/g, ''));
      var sisa_pagu_jalan = limit_rawat_jalan - sum_penggantian_jalan;
      $('#temp_limit_rawat_jalan').val(sisa_pagu_jalan);
    }
  });

  var get_sum_penggantian_inap = "form/get_sum_penggantian_inap";
  $.ajax({
    url: get_sum_penggantian_inap,
    method: 'POST',
    dataType: 'json',
    data: { request_id: request_id },
    success: function (data_inap) {
      var sum_penggantian_inap = Intl.NumberFormat().format(data_inap[0].sum_penggantian);
      $('#sum_penggantian_inap').val(sum_penggantian_inap);
      var limit_rawat_inap = $("#limit_rawat_inap").val();
      sum_penggantian_inap = parseFloat(sum_penggantian_inap.replace(/,/g, ''));
      limit_rawat_inap = parseFloat(limit_rawat_inap.replace(/,/g, ''));
      var sisa_pagu_inap = limit_rawat_inap - sum_penggantian_inap;
      $('#temp_limit_rawat_inap').val(sisa_pagu_inap);
    }
  });

  var get_sum_penggantian_kacamata = "form/get_sum_penggantian_kacamata";
  $.ajax({
    url: get_sum_penggantian_kacamata,
    method: 'POST',
    dataType: 'json',
    data: { request_id: request_id },
    success: function (data_kacamata) {

      var sum_penggantian_kacamata_frame = Intl.NumberFormat().format(data_kacamata[0].sum_frame);
      $('#sum_penggantian_kacamata_frame').val(sum_penggantian_kacamata_frame);
      var limit_kacamata_frame = $("#limit_kacamata_frame").val();
      sum_penggantian_kacamata_frame = parseFloat(sum_penggantian_kacamata_frame.replace(/,/g, ''));
      limit_kacamata_frame = parseFloat(limit_kacamata_frame.replace(/,/g, ''));
      var sisa_pagu_frame = limit_kacamata_frame - sum_penggantian_kacamata_frame;
      $('#temp_limit_kacamata_frame').val(sisa_pagu_frame);

      var sum_penggantian_kacamata_one_focus = Intl.NumberFormat().format(data_kacamata[0].sum_one_focus);
      $('#sum_penggantian_kacamata_one_focus').val(sum_penggantian_kacamata_one_focus);
      var limit_kacamata_one_focus = $("#limit_kacamata_one_focus").val();
      sum_penggantian_kacamata_one_focus = parseFloat(sum_penggantian_kacamata_one_focus.replace(/,/g, ''));
      limit_kacamata_one_focus = parseFloat(limit_kacamata_one_focus.replace(/,/g, ''));
      var sisa_pagu_one_focus = limit_kacamata_one_focus - sum_penggantian_kacamata_one_focus;
      $('#temp_limit_kacamata_one_focus').val(sisa_pagu_one_focus);

      var sum_penggantian_kacamata_two_focus = Intl.NumberFormat().format(data_kacamata[0].sum_two_focus);
      $('#sum_penggantian_kacamata_two_focus').val(sum_penggantian_kacamata_two_focus);
      var limit_kacamata_two_focus = $("#limit_kacamata_two_focus").val();
      sum_penggantian_kacamata_two_focus = parseFloat(sum_penggantian_kacamata_two_focus.replace(/,/g, ''));
      limit_kacamata_two_focus = parseFloat(limit_kacamata_two_focus.replace(/,/g, ''));
      var sisa_pagu_two_focus = limit_kacamata_two_focus - sum_penggantian_kacamata_two_focus;
      $('#temp_limit_kacamata_two_focus').val(sisa_pagu_two_focus);

    }
  });

});

$(document).ready(function () {
  $('.select-search').select2({
    dropdownParent: $('#modalAddClaim')
  });

  $('.select-search_couple').select2({
    dropdownParent: $('#modalAddCouple')
  });

  $('.select-kuitansi').select2({
    tags: true
  });

  $('.select-search_user_hris').select2({
    dropdownParent: $('#modalTambahUser')
  });

  // var join_date = $('#join_date').val();
  // var today = new Date(); 
  // var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();

  // alert(date);
  // if((grandparent == 3) || (marital_status == 'Single')){
  //   document.getElementById("pasangan").disabled = true;
  //   document.getElementById("anak").disabled = true;
  // }else{
  //   document.getElementById("pasangan").disabled = false;
  //   document.getElementById("anak").disabled = false;
  // }


});

$('[name="customRadioTor"]').on('change', function () {
  var state = this.id == 'anak';
  $('[name="listanak"]').toggle(state);

  var state_p = this.id == 'pasangan';
  $('[name="listpasangan"]').toggle(state_p);

  var tanggal_kuitansi = $("#tanggal_kuitansi").val();

  if ((tanggal_kuitansi == "") || (tanggal_kuitansi === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Tanggal Kuitansi tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });

    $('.listanak').hide();
    var ele = document.getElementsByName("customRadioTor");
    for (var i = 0; i < ele.length; i++)
      ele[i].checked = false;

    $('.listpasangan').hide();
    var elee = document.getElementsByName("customRadioTor");
    for (var j = 0; j < elee.length; j++)
      elee[j].checked = false;

    return false;
  }

  var getFamilyChild = "form/getFamilyChild";
  $.ajax({
    url: getFamilyChild,
    dataType: 'json',
    type: 'POST',
    data: { tanggal_kuitansi: tanggal_kuitansi },
    success: function (data) {
      $('#listanak').html(data);
    }
  });

  var getFamilySpouse = "form/getFamilySpouse";
  $.ajax({
    url: getFamilySpouse,
    dataType: 'json',
    type: 'POST',
    data: { tanggal_kuitansi: tanggal_kuitansi },
    success: function (data) {
      //alert(data);
      $('#listpasangan').html(data);
    }
  });

});

$('#request_grandparent_tambah').on('change', function () {
  var join_years = $("#join_years").val();
  var join_months = $("#join_months").val();
  var employee_subgroup = $("#employee_subgroup").val();

  var grandparent = $('#request_grandparent_tambah').val();
  var marital_status = $('#marital_status').val();

  if (((grandparent == 3)) && (join_years < 1)) {
    Swal.fire({
      position: 'center',
      icon: 'warning',
      title: 'Belum bisa melakukan pengajuan<br>Masa kerja masih kurang dari satu tahun',
      showConfirmButton: false,
      timer: 2500
    });
    document.getElementById('form_Add_Claim').reset();
    $('#form_Add_Claim').trigger('reset');
    var get_Grandparent = "form/get_Grandparent";
    $.ajax({
      url: get_Grandparent,
      dataType: 'json',
      type: 'POST',
      data: {},
      success: function (data) {
        $('#request_grandparent_tambah').html(data);
        $('#request_parent_tambah').html('<option value="">Sub Penggantian</option>');
        $('#request_child_tambah').html('<option value="">Penggantian</option>');
      }
    });
    return false;

  } else if ((employee_subgroup != 'Contract') && (join_years < 1) && (join_months < 3)) {
    Swal.fire({
      position: 'center',
      icon: 'info',
      title: 'Belum bisa melakukan pengajuan<br>Masa kerja masih kurang dari tiga bulan',
      showConfirmButton: false,
      timer: 2500
    });
    document.getElementById('form_Add_Claim').reset();
    $('#form_Add_Claim').trigger('reset');
    var get_Grandparent = "form/get_Grandparent";
    $.ajax({
      url: get_Grandparent,
      dataType: 'json',
      type: 'POST',
      data: {},
      success: function (data) {
        $('#request_grandparent_tambah').html(data);
        $('#request_parent_tambah').html('<option value="">Sub Penggantian</option>');
        $('#request_child_tambah').html('<option value="">Penggantian</option>');
      }
    });
    return false;
  }


  var kosong = "";
  $('#total_nominal_kuitansi').val(kosong);
  $('#penggantian').val(kosong);

  if (grandparent == 2) {
    $('.s_harga_kamar').show();
  } else {
    $('.s_harga_kamar').hide();
  }

  if ((grandparent == 3)) {
    document.getElementById("pasangan").disabled = true;
    document.getElementById("anak").disabled = true;
  }
  //else{
  //   document.getElementById("pasangan").disabled = false;
  //   document.getElementById("anak").disabled = false;
  // }

});

$('#request_parent_tambah').on('change', function () {
  var parent = $('#request_parent_tambah').val();
  var join_years = $("#join_years").val();
  var join_months = $("#join_months").val();
  var employee_subgroup = $("#employee_subgroup").val();
  var marital_status = $('#marital_status').val();
  var gender = $('#gender').val();
  //alert(marital_status);
  if (((parent == 4) || (parent == 7)) && (join_years < 1)) {
    Swal.fire({
      position: 'center',
      icon: 'info',
      title: 'Belum bisa melakukan pengajuan<br>Masa kerja masih kurang dari satu tahun',
      showConfirmButton: false,
      timer: 2500
    });
    document.getElementById('form_Add_Claim').reset();
    $('#form_Add_Claim').trigger('reset');
    var get_Grandparent = "form/get_Grandparent";
    $.ajax({
      url: get_Grandparent,
      dataType: 'json',
      type: 'POST',
      data: {},
      success: function (data) {
        $('#request_grandparent_tambah').html(data);
        $('#request_parent_tambah').html('<option value="">Sub Penggantian</option>');
        $('#request_child_tambah').html('<option value="">Penggantian</option>');
      }
    });
    return false;
  } else if ((employee_subgroup != 'Contract') && (join_years < 1) && (join_months < 3)) {
    Swal.fire({
      position: 'center',
      icon: 'info',
      title: 'Belum bisa melakukan pengajuan<br>Masa kerja masih kurang dari tiga bulan',
      showConfirmButton: false,
      timer: 2500
    });
    document.getElementById('form_Add_Claim').reset();
    $('#form_Add_Claim').trigger('reset');
    var get_Grandparent = "form/get_Grandparent";
    $.ajax({
      url: get_Grandparent,
      dataType: 'json',
      type: 'POST',
      data: {},
      success: function (data) {
        $('#request_grandparent_tambah').html(data);
        $('#request_parent_tambah').html('<option value="">Sub Penggantian</option>');
        $('#request_child_tambah').html('<option value="">Penggantian</option>');
      }
    });
    return false;
  } else if (((marital_status == 'Div.') || (marital_status == 'Wid.') || (marital_status == 'Single')) && ((parent == 4) || (parent == 7))) {
    Swal.fire({
      position: 'center',
      icon: 'info',
      title: 'Maaf Marital Status anda tidak bisa mengajukan<br>Klaim Kehamilan dan Melahirkan',
      showConfirmButton: false,
      timer: 2500
    });
    document.getElementById('form_Add_Claim').reset();
    $('#form_Add_Claim').trigger('reset');
    var get_Grandparent = "form/get_Grandparent";
    $.ajax({
      url: get_Grandparent,
      dataType: 'json',
      type: 'POST',
      data: {},
      success: function (data) {
        $('#request_grandparent_tambah').html(data);
        $('#request_parent_tambah').html('<option value="">Sub Penggantian</option>');
        $('#request_child_tambah').html('<option value="">Penggantian</option>');
      }
    });
    return false;

  }

  var kosong = "";
  $('#total_nominal_kuitansi').val(kosong);
  $('#penggantian').val(kosong);

});

$('#request_child_tambah').on('change', function () {
  var join_years = $("#join_years").val();
  var join_months = $("#join_months").val();
  var grandparent = $('#request_grandparent_tambah').val();
  var parent = $('#request_parent_tambah').val();
  var child = $('#request_child_tambah').val();

  if ((employee_subgroup != 'Contract') && (join_years < 1) && (join_months < 3)) {
    Swal.fire({
      position: 'center',
      icon: 'info',
      title: 'Belum bisa melakukan pengajuan<br>Masa kerja masih kurang dari tiga bulan',
      showConfirmButton: false,
      timer: 2500
    });
    document.getElementById('form_Add_Claim').reset();
    $('#form_Add_Claim').trigger('reset');
    var get_Grandparent = "form/get_Grandparent";
    $.ajax({
      url: get_Grandparent,
      dataType: 'json',
      type: 'POST',
      data: {},
      success: function (data) {
        $('#request_grandparent_tambah').html(data);
        $('#request_parent_tambah').html('<option value="">Sub Penggantian</option>');
        $('#request_child_tambah').html('<option value="">Penggantian</option>');
      }
    });
    return false;
  }

  var kosong = "";
  $('#total_nominal_kuitansi').val(kosong);
  $('#penggantian').val(kosong);

  if ((grandparent == 2) && (parent == 3) && (child == 18)) {
    $('.s_harga_kamar').show();
  } else if ((grandparent == 2) && (parent == 3) && (child != 18)) {
    $('.s_harga_kamar').hide();
  }

});

$('#harga_kamar').on('change', function () {
  var kosong = "";
  $('#total_nominal_kuitansi').val(kosong);
  $('#penggantian').val(kosong);

});

$('#tanggal_kuitansi').on('change', function () {

  $('.listanak').hide();
  var ele = document.getElementsByName("customRadioTor");
  for (var i = 0; i < ele.length; i++)
    ele[i].checked = false;

  var tanggal_kuitansi = $("#tanggal_kuitansi").val();
  var today = new Date();
  var selisih = Math.floor(hitungSelisihHari(tanggal_kuitansi, today));

  var cek_efektifitas_kuitansi = "form/cek_efektifitas_kuitansi";
  $.ajax({
    url: cek_efektifitas_kuitansi,
    dataType: 'json',
    type: 'POST',
    data: { tanggal_kuitansi: tanggal_kuitansi },
    success: function (efektifitas) {

      var efektifitas = efektifitas;
      if (efektifitas != false) {
        if ((selisih > efektifitas)) {
          Swal.fire({
            position: 'center',
            icon: 'info',
            title: 'Kuitansi sudah tidak bisa diajukan, <br> Kuitansi berlaku sebelum ' + efektifitas + ' Hari pengajuan',
            showConfirmButton: false,
            timer: 3500
          });
          var kosong = "";
          $('#tanggal_kuitansi').val(kosong);
          return false;
        }
      } else {
        Swal.fire({
          position: 'center',
          icon: 'info',
          title: 'Kuitansi sudah tidak bisa diajukan',
          showConfirmButton: false,
          timer: 3500
        });
        var kosong = "";
        $('#tanggal_kuitansi').val(kosong);
        return false;
      }
    }
  });


});

$('#total_nominal_kuitansi').on('change', function () {
  var request_id = $("#request_id").val();
  var total = $("#total_nominal_kuitansi").val();
  total = Intl.NumberFormat().format(total);
  total = parseFloat(total.replace(/,/g, ''));
  var persentase = 100 / 100;
  var request_grandparent_tambah = $("#request_grandparent_tambah").val();
  var request_parent_tambah = $("#request_parent_tambah").val();
  var request_child_tambah = $("#request_child_tambah").val();
  var harga_kamar = $("#harga_kamar").val();

  // alert(request_grandparent_tambah);
  // alert(request_parent_tambah);
  // alert(request_child_tambah);
  //alert(request_parent_tambah);
  // alert(request_child_tambah);

  if ((request_grandparent_tambah == "") || (request_grandparent_tambah === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Jenis Penggantian tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    var kosong = "";
    $('#total_nominal_kuitansi').val(kosong);
    return false;

  } else if ((request_parent_tambah == "") || (request_parent_tambah === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Sub Penggantian tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    var kosong = "";
    $('#total_nominal_kuitansi').val(kosong);
    return false;

  } else if ((request_child_tambah == "") || (request_child_tambah === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Sub Penggantian tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    var kosong = "";
    $('#total_nominal_kuitansi').val(kosong);
    return false;

  } else if (request_grandparent_tambah == 1) {



    var temp_limit_rawat_jalan = $("#temp_limit_rawat_jalan").val();
    //alert(temp_limit_rawat_jalan);
    temp_limit_rawat_jalan = parseFloat(temp_limit_rawat_jalan.replace(/,/g, ''));

    var sum_penggantian_jalan = $("#sum_penggantian_jalan").val();
    sum_penggantian_jalan = parseFloat(sum_penggantian_jalan.replace(/,/g, ''));

    var limit_rawat_jalan = $("#limit_rawat_jalan").val();
    limit_rawat_jalan = parseFloat(limit_rawat_jalan.replace(/,/g, ''));

    var pengganti = total * persentase;



    if (temp_limit_rawat_jalan > (pengganti + sum_penggantian_jalan)) {
      var penggantian = pengganti;
    } else if (temp_limit_rawat_jalan <= 0) {
      var penggantian = 0;
      Swal.fire({
        position: 'center',
        icon: 'info',
        title: 'Penggantian rawat jalan anda sudah habis',
        showConfirmButton: true
      });
    } else {
      var penggantian = temp_limit_rawat_jalan;
    }

    $('#penggantian').val(penggantian);

  } else if (request_grandparent_tambah == 2) {

    var temp_limit_rawat_inap = $("#temp_limit_rawat_inap").val();
    temp_limit_rawat_inap = parseFloat(temp_limit_rawat_inap.replace(/,/g, ''));

    var sum_penggantian_inap = $("#sum_penggantian_inap").val();
    sum_penggantian_inap = parseFloat(sum_penggantian_inap.replace(/,/g, ''));

    var limit_rawat_inap = $("#limit_rawat_inap").val();
    limit_rawat_inap = parseFloat(limit_rawat_inap.replace(/,/g, ''));

    //alert(limit_rawat_inap);

    // if((grandparent == 2) && (parent == 3) && (child == 18)){
    //   $('.s_harga_kamar').show();
    // }else if ((grandparent == 2) && (parent == 3) && (child != 18)){
    //   $('.s_harga_kamar').hide();
    // }

    if ((request_parent_tambah == 3) && (request_child_tambah == 18) && (harga_kamar == "") || (harga_kamar === undefined)) {
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Harga kamar tidak boleh kosong',
        showConfirmButton: false,
        timer: 1500
      });
      return false;
    } else if ((request_parent_tambah != 3) && (request_child_tambah != 18) && (harga_kamar == "") || (harga_kamar === undefined)) {
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Harga kamar tidak boleh kosong',
        showConfirmButton: false,
        timer: 1500
      });
      return false;
    } else if ((request_parent_tambah == 3) && (request_child_tambah != 18) && (harga_kamar == "") || (harga_kamar === undefined)) {

    }

    if (request_child_tambah == 12) {
      var status = 'Normal';
      var cek_limit_maternity = "form/cek_limit_maternity";
      $.ajax({
        url: cek_limit_maternity,
        dataType: 'json',
        type: 'POST',
        data: { status: status },
        success: function (data_lim_mat) {
          var lim_mat = data_lim_mat;
          if (lim_mat <= total) {

            Swal.fire({
              position: 'center',
              icon: 'info',
              title: 'Total Kuitansi lebih besar dari Pagu Maternity (Kelahiran Normal), <br>Akan dilakukan penyesuaian penggantian',
              showConfirmButton: true
            });

            total = lim_mat;
            var cek_limit_harga_kamar = "form/cek_limit_harga_kamar";
            $.ajax({
              url: cek_limit_harga_kamar,
              dataType: 'json',
              type: 'POST',
              data: {},
              success: function (data_limit) {
                var limit_kamar = parseFloat(data_limit);

                if (limit_kamar <= harga_kamar) {

                  var pengganti = Math.round(((limit_kamar / harga_kamar) * (total * persentase)));
                  if (temp_limit_rawat_inap > (pengganti + sum_penggantian_inap)) {
                    var penggantian = pengganti;
                  } else if (temp_limit_rawat_inap <= 0) {
                    var penggantian = 0;
                    Swal.fire({
                      position: 'center',
                      icon: 'info',
                      title: 'Penggantian rawat inap anda sudah habis',
                      showConfirmButton: true
                    });
                  } else {
                    if (total >= temp_limit_rawat_inap) {
                      var penggantian = total;
                    } else {
                      var penggantian = temp_limit_rawat_inap;
                    }

                  }

                } else {
                  if (total >= lim_mat) {
                    var pengganti = lim_mat * persentase;
                  } else {
                    var pengganti = total * persentase;
                  }
                  if (temp_limit_rawat_inap > (pengganti + sum_penggantian_inap)) {
                    var penggantian = pengganti;
                  } else if (temp_limit_rawat_inap <= 0) {
                    var penggantian = 0;
                    Swal.fire({
                      position: 'center',
                      icon: 'info',
                      title: 'Penggantian rawat inap anda sudah habis',
                      showConfirmButton: true
                    });
                  } else {
                    if (lim_mat >= temp_limit_rawat_inap) {
                      var penggantian = lim_mat;
                    } else {
                      var penggantian = temp_limit_rawat_inap;
                    }
                  }

                }

                $('#penggantian').val(penggantian);
              }
            });


          } else {
            var cek_limit_harga_kamar = "form/cek_limit_harga_kamar";
            $.ajax({
              url: cek_limit_harga_kamar,
              dataType: 'json',
              type: 'POST',
              data: {},
              success: function (data_limit) {
                var limit_kamar = parseFloat(data_limit);
                if (limit_kamar <= harga_kamar) {

                  var pengganti = Math.round(((limit_kamar / harga_kamar) * total) * persentase);
                  if (temp_limit_rawat_inap > (pengganti + sum_penggantian_inap)) {
                    var penggantian = pengganti;
                  } else if (temp_limit_rawat_inap <= 0) {
                    var penggantian = 0;
                    Swal.fire({
                      position: 'center',
                      icon: 'info',
                      title: 'Penggantian rawat inap anda sudah habis',
                      showConfirmButton: true
                    });
                  } else {
                    var penggantian = temp_limit_rawat_inap;
                  }

                } else {

                  var pengganti = total * persentase;
                  if (temp_limit_rawat_inap > (pengganti + sum_penggantian_inap)) {
                    var penggantian = pengganti;
                  } else if (temp_limit_rawat_inap <= 0) {
                    var penggantian = 0;
                    Swal.fire({
                      position: 'center',
                      icon: 'info',
                      title: 'Penggantian rawat inap anda sudah habis',
                      showConfirmButton: true
                    });
                  } else {
                    var penggantian = temp_limit_rawat_inap;
                  }

                }

                $('#penggantian').val(penggantian);
              }
            });

          }


        }
      });


    } else if (request_child_tambah == 13) {

      var status = 'Caesar';
      var cek_limit_maternity = "form/cek_limit_maternity";
      $.ajax({
        url: cek_limit_maternity,
        dataType: 'json',
        type: 'POST',
        data: { status: status },
        success: function (data_lim_mat) {
          var lim_mat = data_lim_mat;
          if (lim_mat <= total) {

            Swal.fire({
              position: 'center',
              icon: 'info',
              title: 'Total Kuitansi lebih besar dari Pagu Maternity (Kelahiran Caesar), <br>Akan dilakukan penyesuaian penggantian',
              showConfirmButton: true
            });

            total = lim_mat;
            var cek_limit_harga_kamar = "form/cek_limit_harga_kamar";
            $.ajax({
              url: cek_limit_harga_kamar,
              dataType: 'json',
              type: 'POST',
              data: {},
              success: function (data_limit) {
                var limit_kamar = parseFloat(data_limit);

                if (limit_kamar <= harga_kamar) {

                  var pengganti = Math.round(((limit_kamar / harga_kamar) * (total * persentase)));
                  if (temp_limit_rawat_inap > (pengganti + sum_penggantian_inap)) {
                    var penggantian = pengganti;
                  } else if (temp_limit_rawat_inap <= 0) {
                    var penggantian = 0;
                    Swal.fire({
                      position: 'center',
                      icon: 'info',
                      title: 'Penggantian rawat inap anda sudah habis',
                      showConfirmButton: true
                    });
                  } else {
                    if (total >= temp_limit_rawat_inap) {
                      var penggantian = total;
                    } else {
                      var penggantian = temp_limit_rawat_inap;
                    }

                  }

                } else {
                  if (total >= lim_mat) {
                    var pengganti = lim_mat * persentase;
                  } else {
                    var pengganti = total * persentase;
                  }
                  if (temp_limit_rawat_inap > (pengganti + sum_penggantian_inap)) {
                    var penggantian = pengganti;
                  } else if (temp_limit_rawat_inap <= 0) {
                    var penggantian = 0;
                    Swal.fire({
                      position: 'center',
                      icon: 'info',
                      title: 'Penggantian rawat inap anda sudah habis',
                      showConfirmButton: true
                    });
                  } else {
                    if (lim_mat >= temp_limit_rawat_inap) {
                      var penggantian = lim_mat;
                    } else {
                      var penggantian = temp_limit_rawat_inap;
                    }
                  }

                }

                $('#penggantian').val(penggantian);
              }
            });


          } else {
            var cek_limit_harga_kamar = "form/cek_limit_harga_kamar";
            $.ajax({
              url: cek_limit_harga_kamar,
              dataType: 'json',
              type: 'POST',
              data: {},
              success: function (data_limit) {
                var limit_kamar = parseFloat(data_limit);
                if (limit_kamar <= harga_kamar) {

                  var pengganti = Math.round(((limit_kamar / harga_kamar) * total) * persentase);
                  if (temp_limit_rawat_inap > (pengganti + sum_penggantian_inap)) {
                    var penggantian = pengganti;
                  } else if (temp_limit_rawat_inap <= 0) {
                    var penggantian = 0;
                    Swal.fire({
                      position: 'center',
                      icon: 'info',
                      title: 'Penggantian rawat inap anda sudah habis',
                      showConfirmButton: true
                    });
                  } else {
                    var penggantian = temp_limit_rawat_inap;
                  }

                } else {

                  var pengganti = total * persentase;
                  if (temp_limit_rawat_inap > (pengganti + sum_penggantian_inap)) {
                    var penggantian = pengganti;
                  } else if (temp_limit_rawat_inap <= 0) {
                    var penggantian = 0;
                    Swal.fire({
                      position: 'center',
                      icon: 'info',
                      title: 'Penggantian rawat inap anda sudah habis',
                      showConfirmButton: true
                    });
                  } else {
                    var penggantian = temp_limit_rawat_inap;
                  }

                }

                $('#penggantian').val(penggantian);
              }
            });

          }


        }
      });


    } else if (request_child_tambah == 14) {

      var status = 'Normal';
      var cek_limit_maternity = "form/cek_limit_maternity";
      $.ajax({
        url: cek_limit_maternity,
        dataType: 'json',
        type: 'POST',
        data: { status: status },
        success: function (data_lim_mat) {
          var lim_mat = data_lim_mat;
          lim_mat = (lim_mat * (50 / 100));
          //alert(lim_mat);
          if (lim_mat <= total) {

            Swal.fire({
              position: 'center',
              icon: 'info',
              title: 'Total Kuitansi lebih besar dari Pagu Maternity (Keguguran), <br>Akan dilakukan penyesuaian penggantian',
              showConfirmButton: true
            });

            total = lim_mat;
            var cek_limit_harga_kamar = "form/cek_limit_harga_kamar";
            $.ajax({
              url: cek_limit_harga_kamar,
              dataType: 'json',
              type: 'POST',
              data: {},
              success: function (data_limit) {
                var limit_kamar = data_limit;

                if (limit_kamar <= harga_kamar) {

                  var pengganti = Math.round(((limit_kamar / harga_kamar) * (total * persentase)));
                  if (temp_limit_rawat_inap > (pengganti + sum_penggantian_inap)) {
                    var penggantian = pengganti;
                  } else if (temp_limit_rawat_inap <= 0) {
                    var penggantian = 0;
                    Swal.fire({
                      position: 'center',
                      icon: 'info',
                      title: 'Penggantian rawat inap anda sudah habis',
                      showConfirmButton: true
                    });
                  } else {
                    if (total >= temp_limit_rawat_inap) {
                      var penggantian = total;
                    } else {
                      var penggantian = temp_limit_rawat_inap;
                    }

                  }

                } else {

                  var pengganti = total * persentase;
                  if (temp_limit_rawat_inap > (pengganti + sum_penggantian_inap)) {
                    var penggantian = pengganti;
                  } else if (temp_limit_rawat_inap <= 0) {
                    var penggantian = 0;
                    Swal.fire({
                      position: 'center',
                      icon: 'info',
                      title: 'Penggantian rawat inap anda sudah habis',
                      showConfirmButton: true
                    });
                  } else {
                    if (lim_mat >= temp_limit_rawat_inap) {
                      var penggantian = lim_mat;
                    } else {
                      var penggantian = temp_limit_rawat_inap;
                    }
                  }

                }

                $('#penggantian').val(penggantian);
              }
            });


          } else {
            var cek_limit_harga_kamar = "form/cek_limit_harga_kamar";
            $.ajax({
              url: cek_limit_harga_kamar,
              dataType: 'json',
              type: 'POST',
              data: {},
              success: function (data_limit) {
                var limit_kamar = data_limit;
                if (limit_kamar <= harga_kamar) {

                  var pengganti = Math.round(((limit_kamar / harga_kamar) * total) * persentase);
                  if (temp_limit_rawat_inap > (pengganti + sum_penggantian_inap)) {
                    var penggantian = pengganti;
                  } else if (temp_limit_rawat_inap <= 0) {
                    var penggantian = 0;
                    Swal.fire({
                      position: 'center',
                      icon: 'info',
                      title: 'Penggantian rawat inap anda sudah habis',
                      showConfirmButton: true
                    });
                  } else {
                    var penggantian = temp_limit_rawat_inap;
                  }

                } else {

                  var pengganti = total * persentase;
                  if (temp_limit_rawat_inap > (pengganti + sum_penggantian_inap)) {
                    var penggantian = pengganti;
                  } else if (temp_limit_rawat_inap <= 0) {
                    var penggantian = 0;
                    Swal.fire({
                      position: 'center',
                      icon: 'info',
                      title: 'Penggantian rawat inap anda sudah habis',
                      showConfirmButton: true
                    });
                  } else {
                    var penggantian = temp_limit_rawat_inap;
                  }

                }

                $('#penggantian').val(penggantian);
              }
            });

          }


        }
      });



    } else {
      var cek_limit_harga_kamar = "form/cek_limit_harga_kamar";
      $.ajax({
        url: cek_limit_harga_kamar,
        dataType: 'json',
        type: 'POST',
        data: {},
        success: function (data_limit) {
          var limit_kamar = parseFloat(data_limit);

          if (limit_kamar <= harga_kamar) {

            var pengganti = Math.round(((limit_kamar / harga_kamar) * total) * persentase);
            if (temp_limit_rawat_inap > (pengganti + sum_penggantian_inap)) {
              var penggantian = pengganti;
            } else if (temp_limit_rawat_inap <= 0) {
              var penggantian = 0;
              Swal.fire({
                position: 'center',
                icon: 'info',
                title: 'Penggantian rawat inap anda sudah habis',
                showConfirmButton: true
              });
            } else {
              var penggantian = temp_limit_rawat_inap;
            }

          } else {

            var pengganti = total * persentase;
            if (temp_limit_rawat_inap > (pengganti + sum_penggantian_inap)) {
              var penggantian = pengganti;
            } else if (temp_limit_rawat_inap <= 0) {
              var penggantian = 0;
              Swal.fire({
                position: 'center',
                icon: 'info',
                title: 'Penggantian rawat inap anda sudah habis',
                showConfirmButton: true
              });
            } else {
              var penggantian = temp_limit_rawat_inap;
            }

          }

          $('#penggantian').val(penggantian);
        }
      });
    }
  } else if (request_grandparent_tambah == 3) {

    if ((request_parent_tambah == 5) && (request_child_tambah == 15)) {

      var cek_tanggal_pengambilan_kacamata = "form/cek_tanggal_pengambilan_kacamata";
      $.ajax({
        url: cek_tanggal_pengambilan_kacamata,
        dataType: 'json',
        type: 'POST',
        data: { request_id: request_id, request_grandparent_tambah: request_grandparent_tambah, request_parent_tambah: request_parent_tambah, request_child_tambah: request_child_tambah },
        success: function (data_tanggal) {
          if (data_tanggal == 'Kosong') {
            //var jarak_tahun = 1;
          } else {
            var data_tanggal = new Date(data_tanggal);
            var today = new Date();
            var tahun_last_req = data_tanggal.getFullYear();
            var tahun_new_req = today.getFullYear();
            var jarak_tahun = tahun_new_req - tahun_last_req;
          }

          if ((jarak_tahun >= 2) || (data_tanggal == 'Kosong')) {

            var temp_limit_kacamata_frame = $("#temp_limit_kacamata_frame").val();
            temp_limit_kacamata_frame = parseFloat(temp_limit_kacamata_frame.replace(/,/g, ''));

            var sum_penggantian_kacamata_frame = $("#sum_penggantian_kacamata_frame").val();
            sum_penggantian_kacamata_frame = parseFloat(sum_penggantian_kacamata_frame.replace(/,/g, ''));

            var limit_kacamata_frame = $("#limit_kacamata_frame").val();
            limit_kacamata_frame = parseFloat(limit_kacamata_frame.replace(/,/g, ''));

            var pengganti = total * persentase;
            //alert(pengganti);
            if (temp_limit_kacamata_frame > (pengganti + sum_penggantian_kacamata_frame)) {
              var penggantian = pengganti;
            } else if (temp_limit_kacamata_frame <= 0) {
              var penggantian = 0;
              Swal.fire({
                position: 'center',
                icon: 'info',
                title: 'Penggantian kacamata frame anda sudah habis',
                showConfirmButton: true
              });
            } else {
              var penggantian = temp_limit_kacamata_frame;
            }

            $('#penggantian').val(penggantian);

          } else {

            Swal.fire({
              position: 'center',
              icon: 'info',
              title: 'Anda sudah pernah melakukan klaim penggantian kacamata untuk frame, <br>klaim berlaku 2 tahun sekali',
              showConfirmButton: true
            });

          }

        }
      });

    } else if ((request_parent_tambah == 6) && (request_child_tambah == 16)) {

      var cek_tanggal_pengambilan_kacamata = "form/cek_tanggal_pengambilan_kacamata";
      $.ajax({
        url: cek_tanggal_pengambilan_kacamata,
        dataType: 'json',
        type: 'POST',
        data: { request_id: request_id, request_grandparent_tambah: request_grandparent_tambah, request_parent_tambah: request_parent_tambah, request_child_tambah: request_child_tambah },
        success: function (data_tanggal) {
          if (data_tanggal == 'Kosong') {
            //var jarak_tahun = 1;
          } else {
            var data_tanggal = new Date(data_tanggal);
            var today = new Date();
            var tahun_last_req = data_tanggal.getFullYear();
            var tahun_new_req = today.getFullYear();
            var jarak_tahun = tahun_new_req - tahun_last_req;
          }

          if ((jarak_tahun >= 1) || (data_tanggal == 'Kosong')) {

            var temp_limit_kacamata_one_focus = $("#temp_limit_kacamata_one_focus").val();
            temp_limit_kacamata_one_focus = parseFloat(temp_limit_kacamata_one_focus.replace(/,/g, ''));

            var sum_penggantian_kacamata_one_focus = $("#sum_penggantian_kacamata_one_focus").val();
            sum_penggantian_kacamata_one_focus = parseFloat(sum_penggantian_kacamata_one_focus.replace(/,/g, ''));

            var limit_kacamata_one_focus = $("#limit_kacamata_one_focus").val();
            limit_kacamata_one_focus = parseFloat(limit_kacamata_one_focus.replace(/,/g, ''));

            var pengganti = total * persentase;

            if (temp_limit_kacamata_one_focus > (pengganti + sum_penggantian_kacamata_one_focus)) {
              var penggantian = pengganti;
            } else if (temp_limit_kacamata_one_focus <= 0) {
              var penggantian = 0;
              Swal.fire({
                position: 'center',
                icon: 'info',
                title: 'Penggantian kacamata one focus anda sudah habis',
                showConfirmButton: true
              });
            } else {
              var penggantian = temp_limit_kacamata_one_focus;
            }
            //alert('test');
            $('#penggantian').val(penggantian);

          } else {

            Swal.fire({
              position: 'center',
              icon: 'info',
              title: 'Anda sudah pernah melakukan klaim penggantian kacamata untuk lensa, <br>klaim berlaku 1 tahun sekali',
              showConfirmButton: true
            });

            document.getElementById('form_Add_Claim').reset();
            $('#form_Add_Claim').trigger('reset');
            var get_Grandparent = "form/get_Grandparent";
            $.ajax({
              url: get_Grandparent,
              dataType: 'json',
              type: 'POST',
              data: {},
              success: function (data) {
                $('#request_grandparent_tambah').html(data);
                $('#request_parent_tambah').html('<option value="">Sub Penggantian</option>');
                $('#request_child_tambah').html('<option value="">Penggantian</option>');
              }
            });

          }
        }

      });

    } else if ((request_parent_tambah == 6) && (request_child_tambah == 17)) {

      var cek_tanggal_pengambilan_kacamata = "form/cek_tanggal_pengambilan_kacamata";
      $.ajax({
        url: cek_tanggal_pengambilan_kacamata,
        dataType: 'json',
        type: 'POST',
        data: { request_id: request_id, request_grandparent_tambah: request_grandparent_tambah, request_parent_tambah: request_parent_tambah, request_child_tambah: request_child_tambah },
        success: function (data_tanggal) {
          //alert(data_tanggal);
          if (data_tanggal == 'Kosong') {
            //var jarak_tahun = 1;
          } else {
            var data_tanggal = new Date(data_tanggal);
            var today = new Date();
            var tahun_last_req = data_tanggal.getFullYear();
            var tahun_new_req = today.getFullYear();
            var jarak_tahun = tahun_new_req - tahun_last_req;
          }

          if ((jarak_tahun >= 1) || (data_tanggal == 'Kosong')) {

            var temp_limit_kacamata_two_focus = $("#temp_limit_kacamata_two_focus").val();
            temp_limit_kacamata_two_focus = parseFloat(temp_limit_kacamata_two_focus.replace(/,/g, ''));

            var sum_penggantian_kacamata_two_focus = $("#sum_penggantian_kacamata_two_focus").val();
            sum_penggantian_kacamata_two_focus = parseFloat(sum_penggantian_kacamata_two_focus.replace(/,/g, ''));

            var limit_kacamata_two_focus = $("#limit_kacamata_two_focus").val();
            limit_kacamata_two_focus = parseFloat(limit_kacamata_two_focus.replace(/,/g, ''));

            var pengganti = total * persentase;

            if (temp_limit_kacamata_two_focus > (pengganti + sum_penggantian_kacamata_two_focus)) {
              var penggantian = pengganti;
            } else if (temp_limit_kacamata_two_focus <= 0) {
              var penggantian = 0;
              Swal.fire({
                position: 'center',
                icon: 'info',
                title: 'Penggantian kacamata two focus anda sudah habis',
                showConfirmButton: true
              });
            } else {
              var penggantian = temp_limit_kacamata_two_focus;
            }

            $('#penggantian').val(penggantian);

          } else {

            Swal.fire({
              position: 'center',
              icon: 'info',
              title: 'Anda sudah pernah melakukan klaim penggantian kacamata untuk lensa, <br>klaim berlaku 1 tahun sekali',
              showConfirmButton: true
            });

            document.getElementById('form_Add_Claim').reset();
            $('#form_Add_Claim').trigger('reset');
            var get_Grandparent = "form/get_Grandparent";
            $.ajax({
              url: get_Grandparent,
              dataType: 'json',
              type: 'POST',
              data: {},
              success: function (data) {
                $('#request_grandparent_tambah').html(data);
                $('#request_parent_tambah').html('<option value="">Sub Penggantian</option>');
                $('#request_child_tambah').html('<option value="">Penggantian</option>');
              }
            });

          }
        }

      });
    }

  }

});


$('.req_medical_tambah').click(function () {

  var request_id = $("#request_id").val();
  var tor_grandparent = $("#request_grandparent_tambah").val();
  var tor_parent = $("#request_parent_tambah").val();
  var tor_child = $("#request_child_tambah").val();
  var jumlah_kuitansi = $("#jumlah_kuitansi").val();
  var total_kuitansi = $("#total_nominal_kuitansi").val();
  var tanggal_kuitansi = $("#tanggal_kuitansi").val();
  var docter = $("#docter").val();
  var diagnosa = $("#diagnosa").val();
  var penggantian = $("#penggantian").val();

  var join_years = $("#join_years").val();
  var join_months = $("#join_months").val();
  var employee_subgroup = $("#employee_subgroup").val();
  var today = new Date();
  var selisih = Math.floor(hitungSelisihHari(tanggal_kuitansi, today));

  if ((tor_grandparent == "") || (tor_grandparent === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Jenis Penggantian tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((tor_parent == "") || (tor_parent === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Sub Penggantian tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((tor_child == "") || (tor_child === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Detail Penggantian tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((jumlah_kuitansi == "") || (jumlah_kuitansi === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Jumlah Kuitansi tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((total_kuitansi == "") || (total_kuitansi === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Total Kuitansi tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((docter == "") || (docter === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Dokter tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((diagnosa == "") || (diagnosa === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Diagnosa tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((tanggal_kuitansi == "") || (tanggal_kuitansi === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Tanggal Kuitansi tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((penggantian == "") || (penggantian === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Penggantian tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if ((penggantian <= 0)) {
    Swal.fire({
      position: 'center',
      icon: 'info',
      title: 'Saldo Penggantian telah habis',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  } else if (((tor_grandparent == 3) || (tor_parent == 4)) && (join_years < 1)) {
    Swal.fire({
      position: 'center',
      icon: 'info',
      title: 'Belum bisa melakukan pengajuan<br>Masa kerja masih kurang dari satu tahun',
      showConfirmButton: false,
      timer: 2500
    });
    return false;
  } else if ((employee_subgroup != 'Contract') && (join_years < 1) && (join_months < 3)) {
    Swal.fire({
      position: 'center',
      icon: 'info',
      title: 'Belum bisa melakukan pengajuan<br>Masa kerja masih kurang dari tiga bulan',
      showConfirmButton: false,
      timer: 2500
    });
    return false;
  } else if ((selisih > 45)) {
    Swal.fire({
      position: 'center',
      icon: 'info',
      title: 'Kuitansi sudah tidak bisa diajukan',
      showConfirmButton: false,
      timer: 2500
    });
    return false;
  }


  var request_family_a = document.getElementsByName('customRadioTor');
  var request_family;
  for (var i = 0; i < request_family_a.length; i++) {
    if (request_family_a[i].checked) {
      request_family = request_family_a[i].value;
    }
  }
  if (request_family === undefined) {
    request_family = ''
  }

  if ((request_family == "") || (request_family === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Keterangan tidak boleh kosong',
      showConfirmButton: false,
      timer: 1500
    });
    return false;
  }

  var anak = $("#listanak").val();
  var pasangan = $("#listpasangan").val();

  if (request_family === 'Diri Sendiri') {
    var additional = 'Diri Sendiri';
  } else if (request_family === 'Pasangan') {
    if ((pasangan == "") || (pasangan === undefined)) {
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Pasangan tidak boleh kosong',
        showConfirmButton: false,
        timer: 1500
      });
      return false;
    }
    var additional = pasangan;
  } else if (request_family === 'Anak') {
    if ((anak == "") || (anak === undefined)) {
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Anak tidak boleh kosong',
        showConfirmButton: false,
        timer: 1500
      });
      return false;
    }
    var additional = anak;
  }

  var url = "form/tambah_tor";

  $.ajax({
    url: url,
    dataType: 'json',
    type: 'POST',
    data: { request_id: request_id, tor_grandparent: tor_grandparent, tor_parent: tor_parent, tor_child: tor_child, jumlah_kuitansi: jumlah_kuitansi, total_kuitansi: total_kuitansi, penggantian: penggantian, request_family: request_family, additional: additional, docter: docter, diagnosa: diagnosa, tanggal_kuitansi: tanggal_kuitansi },
    success: function (data) {
      if (data == true) {
        $('#ToR').DataTable().ajax.reload();
        $('#form_Add_Claim').trigger("reset");
        $("#request_grandparent_tambah").select2('', 'Jenis Penggantian');
        $('#request_parent_tambah').html('<option value=""></option>');
        $('#request_child_tambah').html('<option value=""></option>');
        $('#modalAddClaim').modal('toggle');


        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Data telah ditambahkan',
          showConfirmButton: false,
          timer: 2500
        });

        var get_sum_penggantian_jalan = "form/get_sum_penggantian_jalan";
        $.ajax({
          url: get_sum_penggantian_jalan,
          method: 'POST',
          dataType: 'json',
          data: { request_id: request_id },
          success: function (data_jalan) {
            var sum_penggantian_jalan = Intl.NumberFormat().format(data_jalan[0].sum_penggantian);
            $('#sum_penggantian_jalan').val(sum_penggantian_jalan);
            var limit_rawat_jalan = $("#limit_rawat_jalan").val();
            sum_penggantian_jalan = parseFloat(sum_penggantian_jalan.replace(/,/g, ''));
            limit_rawat_jalan = parseFloat(limit_rawat_jalan.replace(/,/g, ''));
            var sisa_pagu_jalan = limit_rawat_jalan - sum_penggantian_jalan;
            $('#temp_limit_rawat_jalan').val(sisa_pagu_jalan);

            if (sum_penggantian_jalan > limit_rawat_jalan) {
              Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Over limit rawat jalan',
                showConfirmButton: true
              });
            }
          }
        });

        var get_sum_penggantian_inap = "form/get_sum_penggantian_inap";
        $.ajax({
          url: get_sum_penggantian_inap,
          method: 'POST',
          dataType: 'json',
          data: { request_id: request_id },
          success: function (data_inap) {
            var sum_penggantian_inap = Intl.NumberFormat().format(data_inap[0].sum_penggantian);
            $('#sum_penggantian_inap').val(sum_penggantian_inap);
            var limit_rawat_inap = $("#limit_rawat_inap").val();
            sum_penggantian_inap = parseFloat(sum_penggantian_inap.replace(/,/g, ''));
            limit_rawat_inap = parseFloat(limit_rawat_inap.replace(/,/g, ''));
            var sisa_pagu_inap = limit_rawat_inap - sum_penggantian_inap;
            $('#temp_limit_rawat_inap').val(sisa_pagu_inap);
            if (sum_penggantian_inap > limit_rawat_inap) {
              Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Over limit rawat inap',
                showConfirmButton: true
              });
            }
          }
        });

        var get_sum_penggantian_kacamata = "form/get_sum_penggantian_kacamata";
        $.ajax({
          url: get_sum_penggantian_kacamata,
          method: 'POST',
          dataType: 'json',
          data: { request_id: request_id },
          success: function (data_kacamata) {

            var sum_penggantian_kacamata_frame = Intl.NumberFormat().format(data_kacamata[0].sum_frame);
            $('#sum_penggantian_kacamata_frame').val(sum_penggantian_kacamata_frame);
            var limit_kacamata_frame = $("#limit_kacamata_frame").val();
            sum_penggantian_kacamata_frame = parseFloat(sum_penggantian_kacamata_frame.replace(/,/g, ''));
            limit_kacamata_frame = parseFloat(limit_kacamata_frame.replace(/,/g, ''));
            var sisa_pagu_frame = limit_kacamata_frame - sum_penggantian_kacamata_frame;
            $('#temp_limit_kacamata_frame').val(sisa_pagu_frame);

            if (sum_penggantian_kacamata_frame > limit_kacamata_frame) {
              Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Over limit frame kacamata',
                showConfirmButton: true
              });
            }

            var sum_penggantian_kacamata_one_focus = Intl.NumberFormat().format(data_kacamata[0].sum_one_focus);
            $('#sum_penggantian_kacamata_one_focus').val(sum_penggantian_kacamata_one_focus);
            var limit_kacamata_one_focus = $("#limit_kacamata_one_focus").val();
            sum_penggantian_kacamata_one_focus = parseFloat(sum_penggantian_kacamata_one_focus.replace(/,/g, ''));
            limit_kacamata_one_focus = parseFloat(limit_kacamata_one_focus.replace(/,/g, ''));
            var sisa_pagu_one_focus = limit_kacamata_one_focus - sum_penggantian_kacamata_one_focus;
            $('#temp_limit_kacamata_one_focus').val(sisa_pagu_one_focus);

            if (sum_penggantian_kacamata_one_focus > limit_kacamata_one_focus) {
              Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Over limit kacamata one focus',
                showConfirmButton: true
              });
            }

            var sum_penggantian_kacamata_two_focus = Intl.NumberFormat().format(data_kacamata[0].sum_two_focus);
            $('#sum_penggantian_kacamata_two_focus').val(sum_penggantian_kacamata_two_focus);
            var limit_kacamata_two_focus = $("#limit_kacamata_two_focus").val();
            sum_penggantian_kacamata_two_focus = parseFloat(sum_penggantian_kacamata_two_focus.replace(/,/g, ''));
            limit_kacamata_two_focus = parseFloat(limit_kacamata_two_focus.replace(/,/g, ''));
            var sisa_pagu_two_focus = limit_kacamata_two_focus - sum_penggantian_kacamata_two_focus;
            $('#temp_limit_kacamata_two_focus').val(sisa_pagu_two_focus);

            if (sum_penggantian_kacamata_two_focus > limit_kacamata_two_focus) {
              Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Over limit kacamata two focus',
                showConfirmButton: true
              });
            }

          }
        });

      }
    },
    error: function (data) {
      $('#ToR').DataTable().ajax.reload();
      document.getElementById("form_Add_Claim").reset();
      $('#form_Add_Claim').trigger("reset");
      $("#request_grandparent_tambah").select2('', 'Jenis Penggantian');
      $('#request_parent_tambah').html('<option value=""></option>');
      $('#request_child_tambah').html('<option value=""></option>');
      $('#modalAddClaim').modal('toggle');
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Data tidak dapat ditambahkan',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });
});

function delete_tor(delete_tor) {
  var id = document.getElementById(delete_tor).getAttribute("id");
  var request_id = $(".delete_tor").attr("data");

  var url = 'form/delete_tor/';
  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'json',
    data: { id: id },
    success: function (data) {
      $('#ToR').DataTable().ajax.reload();

      var get_sum_penggantian_jalan = "form/get_sum_penggantian_jalan";
      $.ajax({
        url: get_sum_penggantian_jalan,
        method: 'POST',
        dataType: 'json',
        data: { request_id: request_id },
        success: function (data_jalan) {
          var sum_penggantian_jalan = Intl.NumberFormat().format(data_jalan[0].sum_penggantian);
          $('#sum_penggantian_jalan').val(sum_penggantian_jalan);
          sum_penggantian_jalan = parseFloat(sum_penggantian_jalan.replace(/,/g, ''));
          var limit_rawat_jalan = $("#limit_rawat_jalan").val();
          limit_rawat_jalan = parseFloat(limit_rawat_jalan.replace(/,/g, ''));
          var sisa_pagu_jalan = limit_rawat_jalan - sum_penggantian_jalan;
          $('#temp_limit_rawat_jalan').val(sisa_pagu_jalan);

        }
      });

      var get_sum_penggantian_inap = "form/get_sum_penggantian_inap";
      $.ajax({
        url: get_sum_penggantian_inap,
        method: 'POST',
        dataType: 'json',
        data: { request_id: request_id },
        success: function (data_inap) {
          var sum_penggantian_inap = Intl.NumberFormat().format(data_inap[0].sum_penggantian);
          $('#sum_penggantian_inap').val(sum_penggantian_inap);
          sum_penggantian_inap = parseFloat(sum_penggantian_inap.replace(/,/g, ''));
          var limit_rawat_inap = $("#limit_rawat_inap").val();
          limit_rawat_inap = parseFloat(limit_rawat_inap.replace(/,/g, ''));
          var sisa_pagu_inap = limit_rawat_inap - sum_penggantian_inap;
          $('#temp_limit_rawat_inap').val(sisa_pagu_inap);

        }
      });

      var get_sum_penggantian_kacamata = "form/get_sum_penggantian_kacamata";
      $.ajax({
        url: get_sum_penggantian_kacamata,
        method: 'POST',
        dataType: 'json',
        data: { request_id: request_id },
        success: function (data_kacamata) {
          var sum_penggantian_kacamata_frame = Intl.NumberFormat().format(data_kacamata[0].sum_frame);
          $('#sum_penggantian_kacamata_frame').val(sum_penggantian_kacamata_frame);
          sum_penggantian_kacamata_frame = parseFloat(sum_penggantian_kacamata_frame.replace(/,/g, ''));
          var limit_kacamata_frame = $("#limit_kacamata_frame").val();
          limit_kacamata_frame = parseFloat(limit_kacamata_frame.replace(/,/g, ''));
          var sisa_pagu_frame = limit_kacamata_frame - sum_penggantian_kacamata_frame;
          $('#temp_limit_kacamata_frame').val(sisa_pagu_frame);

          var sum_penggantian_kacamata_one_focus = Intl.NumberFormat().format(data_kacamata[0].sum_one_focus);
          $('#sum_penggantian_kacamata_one_focus').val(sum_penggantian_kacamata_one_focus);
          sum_penggantian_kacamata_one_focus = parseFloat(sum_penggantian_kacamata_one_focus.replace(/,/g, ''));
          var limit_kacamata_one_focus = $("#limit_kacamata_one_focus").val();
          limit_kacamata_one_focus = parseFloat(limit_kacamata_one_focus.replace(/,/g, ''));
          var sisa_pagu_one_focus = limit_kacamata_one_focus - sum_penggantian_kacamata_one_focus;
          $('#temp_limit_kacamata_one_focus').val(sisa_pagu_one_focus);

          var sum_penggantian_kacamata_two_focus = Intl.NumberFormat().format(data_kacamata[0].sum_two_focus);
          $('#sum_penggantian_kacamata_two_focus').val(sum_penggantian_kacamata_two_focus);
          sum_penggantian_kacamata_two_focus = parseFloat(sum_penggantian_kacamata_two_focus.replace(/,/g, ''));
          var limit_kacamata_two_focus = $("#limit_kacamata_two_focus").val();
          limit_kacamata_two_focus = parseFloat(limit_kacamata_two_focus.replace(/,/g, ''));
          var sisa_pagu_two_focus = limit_kacamata_two_focus - sum_penggantian_kacamata_two_focus;
          $('#temp_limit_kacamata_two_focus').val(sisa_pagu_two_focus);

        }
      });

      Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Item deleted',
        showConfirmButton: false,
        timer: 1500
      });
      return false;
    }
  });
}


///////////////////////////////////New Request MDCR/////////////////////////////////

$('#submit_new_request_mdcr').click(function () {

  var request_id = $("#id_request").val();
  var is_status = $("#is_status").val();

  var action = $("#action").val();

  var limit_rawat_jalan = $("#limit_rawat_jalan").val();
  limit_rawat_jalan = parseFloat(limit_rawat_jalan.replace(/,/g, ''));
  var sum_penggantian_jalan = $("#sum_penggantian_jalan").val();
  sum_penggantian_jalan = parseFloat(sum_penggantian_jalan.replace(/,/g, ''));

  var limit_rawat_inap = $("#limit_rawat_inap").val();
  limit_rawat_inap = parseFloat(limit_rawat_inap.replace(/,/g, ''));
  var sum_penggantian_inap = $("#sum_penggantian_inap").val();
  sum_penggantian_inap = parseFloat(sum_penggantian_inap.replace(/,/g, ''));

  var limit_kacamata_frame = $("#limit_kacamata_frame").val();
  limit_kacamata_frame = parseFloat(limit_kacamata_frame.replace(/,/g, ''));
  var sum_penggantian_kacamata_frame = $('#sum_penggantian_kacamata_frame').val();
  sum_penggantian_kacamata_frame = parseFloat(sum_penggantian_kacamata_frame.replace(/,/g, ''));

  var limit_kacamata_one_focus = $("#limit_kacamata_one_focus").val();
  limit_kacamata_one_focus = parseFloat(limit_kacamata_one_focus.replace(/,/g, ''));
  var sum_penggantian_kacamata_one_focus = $('#sum_penggantian_kacamata_one_focus').val();
  sum_penggantian_kacamata_one_focus = parseFloat(sum_penggantian_kacamata_one_focus.replace(/,/g, ''));

  var limit_kacamata_two_focus = $("#limit_kacamata_two_focus").val();
  limit_kacamata_two_focus = parseFloat(limit_kacamata_two_focus.replace(/,/g, ''));
  var sum_penggantian_kacamata_two_focus = $('#sum_penggantian_kacamata_two_focus').val();
  sum_penggantian_kacamata_two_focus = parseFloat(sum_penggantian_kacamata_two_focus.replace(/,/g, ''));


  var url_tor = "form/cek_tor";
  $.ajax({
    url: url_tor,
    method: 'POST',
    dataType: 'json',
    data: { request_id: request_id },
    success: function (cek_data_tor) {
      var cek_data_tor = cek_data_tor;
      if (cek_data_tor == 0) {

        Swal.fire({
          position: 'center',
          icon: 'info',
          title: 'Silahkan isi pengajuan klaim,<br>klik "Add Type of Reimbursement"',
          showConfirmButton: false,
          timer: 2500
        });
        return false;

      } else {

        if (limit_rawat_jalan < sum_penggantian_jalan) {
          Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Over limit rawat jalan',
            showConfirmButton: true
          });
          return false;
        }

        if (limit_rawat_inap < sum_penggantian_inap) {
          Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Over limit rawat inap',
            showConfirmButton: true
          });
          return false;
        }

        if (limit_kacamata_frame < sum_penggantian_kacamata_frame) {
          Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Over limit frame kacamata',
            showConfirmButton: true
          });
          return false;
        }

        if (limit_kacamata_one_focus < sum_penggantian_kacamata_one_focus) {
          Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Over limit kacamata one focus',
            showConfirmButton: true
          });
          return false;
        }

        if (limit_kacamata_two_focus < sum_penggantian_kacamata_two_focus) {
          Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Over limit kacamata two focus',
            showConfirmButton: true
          });
          return false;
        }

        if (action == 'Leaving') {
          Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Anda sudah tidak bisa mengajukan Medical Reimbursment',
            showConfirmButton: true
          });
          return false;
        }

        var url = "form/cek_addtional_mdcr";
        $.ajax({
          url: url,
          method: 'POST',
          dataType: 'json',
          data: { request_id: request_id },
          success: function (cek_data) {
            if ((cek_data == "") || (cek_data === undefined)) {

              var form_data = new FormData($('#form_additional_mdcr')[0]);
              var cek_resep = 0;
              var cek_doc;
              var cek_kuitansi;
              for (let [name, value] of form_data) {
                if (name == 'resep') {
                  cek_resep = (name = value);
                }
                if (name == 'kuitansi') {
                  cek_kuitansi = (name = value);
                }
              }
              if (cek_resep == "on") {
                cek_resep = 1;
              };

              if(((cek_resep == "") || (cek_resep === undefined)) && ((cek_kuitansi == "") || (cek_kuitansi === undefined))){
                Swal.fire({
                  position: 'center',
                  icon: 'error',
                  title: 'Additional Information harus diisi,<br>kemudian klik SAVE',
                  showConfirmButton: false,
                  timer: 2500
                });
              }else{
                Swal.fire({
                  position: 'center',
                  icon: 'error',
                  title: 'Jika sudah isi Additional Information,<br>kemudian klik SAVE',
                  showConfirmButton: false,
                  timer: 2500
                });
              }
              return false;
            } else {
              var doc = cek_data[0].documents;
              var kuitansi = cek_data[0].kuitansi;
              var resep = cek_data[0].resep;

              if ((doc == "") || (doc === undefined)) {
                Swal.fire({
                  position: 'center',
                  icon: 'error',
                  title: 'Documents harus diisi',
                  showConfirmButton: false,
                  timer: 1500
                });
                return false;

              } else if ((kuitansi == "") || (kuitansi === undefined)) {
                Swal.fire({
                  position: 'center',
                  icon: 'error',
                  title: 'Kuitansi harus diisi',
                  showConfirmButton: false,
                  timer: 1500
                });
                return false;

              } else {

                var url_apotik = "form/cek_detail_tor_for_apotik";
                $.ajax({
                  url: url_apotik,
                  method: 'POST',
                  dataType: 'json',
                  data: { request_id: request_id, is_status: is_status },
                  success: function (cek_data_apotik) {
                    var cek_data_apotik = cek_data_apotik;

                    if ((cek_data_apotik == "apotik") && (resep == 0)) {


                      Swal.fire({
                        position: 'center',
                        icon: 'info',
                        title: 'Copy Resep Dokter harus ditandai<br>pada form additional information',
                        showConfirmButton: false,
                        timer: 2500
                      });
                      return false;


                    } else {

                      Swal.fire({
                        title: 'Are you sure?',
                        text: '',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Submit'
                      }).then((result) => {
                        if (result.isConfirmed) {

                          var url_sbt = "form/request_submited_mdcr";
                          $.ajax({
                            url: url_sbt,
                            method: 'POST',
                            dataType: 'json',
                            data: { request_id: request_id, is_status: is_status },
                            success: function (data) {
                              Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Your form has been submited.',
                                showConfirmButton: false,
                                timer: 1500
                              });
                              window.location.href = 'home/request'

                            }
                          });
                        }
                      })

                    }

                  }
                });


              }

            }

          },
          error: function (cek_data) {
            Swal.fire({
              position: 'center',
              icon: 'error',
              title: 'Error',
              showConfirmButton: false,
              timer: 1500
            });
            return false;
          }
        });


      }
    },
    error: function (cek_data_tor) {
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Error',
        showConfirmButton: false,
        timer: 1500
      });
      return false;
    }
  });

  // Swal.fire({
  //   title: 'Are you sure?',
  //   text: '',
  //   icon: 'warning',
  //   showCancelButton: true,
  //   confirmButtonColor: '#3085d6',
  //   cancelButtonColor: '#d33',
  //   confirmButtonText: 'Submit'
  // }).then((result) => {
  //   if (result.isConfirmed) {

  //     var url = "form/request_submited_mdcr";
  //       $.ajax({
  //         url: url,
  //         method:'POST',
  //         dataType: 'json',
  //         data:{request_id : request_id},
  //         success:function(data)
  //         {
  //           // Swal.fire(
  //           //   'Submit',
  //           //   'Your form has been submited.',
  //           //   'success'
  //           // )
  //           Swal.fire({
  //             position: 'center',
  //             icon: 'success',
  //             title: 'Your form has been submited.',
  //             showConfirmButton: false,
  //             timer: 1500             
  //           });
  //           window.location.href = 'home/request'

  //         }
  //     });

  //   }
  // })

});

$('.close_tor').click(function () {
  $('#form_Add_Claim').trigger("reset");
  $("#request_grandparent_tambah").select2('', 'Jenis Penggantian');
  $('#request_parent_tambah').html('<option value=""></option>');
  $('#request_child_tambah').html('<option value=""></option>');
});


function success() {
  Swal.fire({
    position: 'center',
    icon: 'success',
    title: 'Your form has been submited.',
    showConfirmButton: false,
    timer: 1500
  });
}

$('#print_out_req_mdcr').click(function () {
  var request_id = $("#request_id").val();
  var request_number = $("#request_number").val();
  window.open("form/print_out_req_mdcr/" + request_id + "/" + request_number, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=700,left=700,width=600,height=600");

});


function delete_couple(delete_couple) {
  var id = document.getElementById(delete_couple).getAttribute("id");
  var url = 'master/delete_couple/';

  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {

      $.ajax({
        type: 'POST',
        url: url,
        dataType: 'json',
        data: { id: id },
        success: function (data) {
          if (data == true) {
            $('#table_couple').DataTable().ajax.reload();
            Swal.fire({
              position: 'center',
              icon: 'info',
              title: 'Data telah dihapus',
              showConfirmButton: false,
              timer: 1500
            });
          } else {
            Swal.fire({
              position: 'center',
              icon: 'error',
              title: 'Data tidak dapat dihapus',
              showConfirmButton: false,
              timer: 1500
            });
          }
        },
        error: function (data) {
          Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Data tidak dapat dihapus',
            showConfirmButton: false,
            timer: 1500
          });
        }
      });

    }
  })


}

//Blfrtip

$(document).ready(function () {
  // $('.test-table thead tr')
  //     .clone(true)
  //     .addClass('filters')
  //     .appendTo('.test-table thead');

  var table = $('.test-table').DataTable({
    ajax: 'master/read/employee/',
    //scrollY: '100%',
    scrollX: true,
    ordering: true,
    searching: true,
    dom:
      "<'row'<'col-sm-6'B><'col-sm-5 text-right'f>>" +
      "<'row'<'col-sm-12'tr>>" +
      "<'row'<'col-sm-4 text-left'l>>" +
      "<'row'<'col-sm-8 text-right'p>>" +
      "<'row'<'col-sm-2'i>>",
    buttons: [
      {
        extend: 'copy',
        text: 'Copy to clipboard'
      },
      'excel',
      'csv',
      // 'pdf',
      'colvis'
    ],
    autoWidth: true,
    pagingType: 'full_numbers',
    orderCellsTop: true,
    fixedHeader: true,
    initComplete: function () {
      var api = this.api();

      api
        .columns()
        .eq(0)
        .each(function (colIdx) {
          var cell = $('.filters th').eq(
            $(api.column(colIdx).header()).index()
          );
          var title = $(cell).text();
          $(cell).html('<input type="text" placeholder="' + title + '" />');

          $(
            'input',
            $('.filters th').eq($(api.column(colIdx).header()).index())
          )
            .off('keyup change')
            .on('change', function (e) {
              // Get the search value
              $(this).attr('title', $(this).val());
              var regexr = '({search})';

              var cursorPosition = this.selectionStart;
              api
                .column(colIdx)
                .search(
                  this.value != ''
                    ? regexr.replace('{search}', '(((' + this.value + ')))')
                    : '',
                  this.value != '',
                  this.value == ''
                )
                .draw();
            })
            .on('keyup', function (e) {
              e.stopPropagation();

              $(this).trigger('change');
              $(this)
                .focus()[0]
                .setSelectionRange(cursorPosition, cursorPosition);
            });
        });
    },
  });
});

$(document).ready(function () {

  var id_request = $("#id_request").val();
  var form_type = $("#form_type").val();
  var employee_id = $("#employee_id").val();
  var is_status = $("#is_status").val();
  var hak_pengajuan = $("#hak_pengajuan").val();

  //Cek Couple MDCR
  if ((form_type == 'MDCR') && (is_status == 0)) {

    if (employee_id != hak_pengajuan) {

      var url = "form/cek_hak_pengajuan";
      $.ajax({
        url: url,
        method: 'POST',
        dataType: 'json',
        data: { hak_pengajuan: hak_pengajuan, id_request: id_request },
        success: function (cek_data) {
          Swal.fire({
            position: 'center',
            icon: 'info',
            title: 'Tidak Bisa Melakukan Pengajuan Medical, <br> Hak Pengajuan Anda Ditanggung Oleh ' + cek_data,
            showConfirmButton: true,
            allowOutsideClick: false,
            allowEscapeKey: false
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = 'dashboard/dashboard'
            }
          })

        }
      });

    }
  }

});

$('#btn_save_add_info').click(function () {

  var request_id = $("#id_request").val();
  var is_status = $("#is_status").val();
  var kuitansi = $("#kuitansi").val();
  var FileDocumentsClaim = $("#FileDocumentsClaim").val();

  if ((kuitansi == "") || (kuitansi === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Kuitansi harus diisi',
      showConfirmButton: false,
      timer: 1500
    });
    return false;

  }

  var url_doc = "form/cek_detail_add_info_for_doc";
  $.ajax({
    url: url_doc,
    method: 'POST',
    dataType: 'json',
    data: { request_id: request_id, is_status: is_status },
    success: function (cek_data_doc) {
      var cek_data_doc = cek_data_doc;

      if ((cek_data_doc == "no_doc") && ((FileDocumentsClaim == "") || (FileDocumentsClaim === undefined))) {

        Swal.fire({
          position: 'center',
          icon: 'error',
          title: 'Documents harus diisi',
          showConfirmButton: false,
          timer: 1500
        });
        return false;

      } else {

        var form_data = new FormData($('#form_additional_mdcr')[0]);
        var resep = 0;
        for (let [name, value] of form_data) {
          if (name == 'resep') {
            resep = (name = value);
          }
        }
        if (resep == "on") {
          resep = 1;
        };

        var url_apotik = "form/cek_detail_tor_for_apotik";
        $.ajax({
          url: url_apotik,
          method: 'POST',
          dataType: 'json',
          data: { request_id: request_id, is_status: is_status },
          success: function (cek_data_apotik) {
            var cek_data_apotik = cek_data_apotik;

            if ((cek_data_apotik == "apotik") && (resep == 0)) {


              Swal.fire({
                position: 'center',
                icon: 'info',
                title: 'Copy Resep Dokter harus ditandai<br>pada form additional information',
                showConfirmButton: false,
                timer: 2500
              });
              return false;


            } else {

              $.ajax({
                type: "POST",
                url: "form/save_additional_mdcr",
                data: form_data,
                processData: false,
                contentType: false,
                success: function (res) {
                  if (res) {
                    Swal.fire({
                      position: 'center',
                      icon: 'success',
                      title: 'Success submitting Additional Information',
                      showConfirmButton: false,
                      timer: 2500
                    });
                    location.reload();
                  } else {
                    Swal.fire({
                      position: 'center',
                      icon: 'Error',
                      title: 'Error submitting Additional Information',
                      showConfirmButton: false,
                      timer: 2500
                    });
                    return false;
                  }
                }
              });
            }

          }
        });

      }
    }
  });

});

$('#tambah_efektifitas_kuitansi').click(function () {
  var efektif_kuitansi_tambah = $("#efektif_kuitansi_tambah").val();
  var start_date_tambah_efektif_kuitansi = $("#start_date_tambah_efektif_kuitansi").val();

  if ((efektif_kuitansi_tambah == "") || (efektif_kuitansi_tambah === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Hari harus diisi',
      showConfirmButton: false,
      timer: 1500
    });
    return false;

  }
  if ((start_date_tambah_efektif_kuitansi == "") || (start_date_tambah_efektif_kuitansi === undefined)) {
    Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Tanggal harus diisi',
      showConfirmButton: false,
      timer: 1500
    });
    return false;

  }
  var url = "master/tambah_efektifitas_kuitansi";

  $.ajax({
    url: url,
    dataType: 'json',
    type: 'POST',
    data: { efektif_kuitansi_tambah: efektif_kuitansi_tambah, start_date_tambah_efektif_kuitansi: start_date_tambah_efektif_kuitansi },
    success: function (data) {
      if (data == true) {
        $('#table_e_kuitansi').DataTable().ajax.reload();
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Data telah ditambahkan',
          showConfirmButton: false,
          timer: 1500
        });
      } else if (data == 'jarak_minus') {
        $('#table_e_kuitansi').DataTable().ajax.reload();
        Swal.fire({
          position: 'center',
          icon: 'warning',
          title: 'Tidak bisa ditambahkan karena,<br> start date masuk dalam kategori role sebelumnya',
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (data) {
      $('#table_e_kuitansi').DataTable().ajax.reload();
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Data tidak dapat ditambahkan',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });


});

function delete_ekuitansi(delete_ekuitansi) {

  Swal.fire({
    title: 'Delete',
    text: 'Are you sure?',
    icon: 'warning',
    width: 'auto',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Delete'
  }).then((result) => {
    if (result.isConfirmed) {

      var id = document.getElementById(delete_ekuitansi).getAttribute("id");
      var url = 'master/getDeleteEkuitansi/';
      $.ajax({
        type: 'POST',
        url: url,
        dataType: 'json',
        data: { id: id },
        success: function (data) {
          if (data == true) {
            $('#table_e_kuitansi').DataTable().ajax.reload();
            Swal.fire({
              position: 'center',
              icon: 'info',
              title: 'Data telah dihapus',
              showConfirmButton: false,
              timer: 1500
            });
          } else {
            Swal.fire({
              position: 'center',
              icon: 'error',
              title: 'Data tidak dapat dihapus',
              showConfirmButton: false,
              timer: 1500
            });
          }
        },
        error: function (data) {
          Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Data tidak dapat dihapus',
            showConfirmButton: false,
            timer: 1500
          });
        }
      });

    }
  })
}

$('.grouping_req_mdcr').click(function () {

  var req_mdcr = new Array();
  var n = $(".checked_id_mdcr:checked").length;
  if (n > 0) {
    $(".checked_id_mdcr:checked").each(function () {
      req_mdcr.push($(this).val());

    });
  }
  var url = 'inbox/grouping_req_mdcr/';

  $.ajax({
    url: url,
    dataType: 'json',
    type: 'POST',
    data: { req_mdcr: req_mdcr },
    success: function (data) {
      if (data == true) {
        Swal.fire({
          position: 'center',
          icon: 'info',
          title: 'Success Grouping',
          showConfirmButton: false,
          timer: 1500
        }).then((result) => {
          location.reload();
        });
      }else if( data == '15_lebih'){
        Swal.fire({
          position: 'center',
          icon: 'info',
          title: 'Silahkan grouping form kurang dari sama dengan 15 request',
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (error) {
      alert('Error2');
      return false;
    }
  });
});


function responseRequest_CekMDCR(type) {
    
  var id = $('#request_id').val();
  //return false;

  if(type == 'Checked_List_MDCR'){
    
      const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
              confirmButton: 'btn btn-primary',
              cancelButton: 'btn btn-light'
          },
          buttonsStyling: false
      })
  
      swalWithBootstrapButtons.fire({
          title: 'Are you sure?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, sure.',
          cancelButtonText: 'Cancel.',
          reverseButtons: true,
          allowOutsideClick: false
      }).then((result) => {
          if (result.value) {
              $.ajax({
                  url: "form/responseRequestFromAdminHR",
                  type: 'post',
                  data: 'id=' + id + '&resp=' + type,
                  dataType: 'json',
                  success: function (response) {
                    if (response == true) {
                      window.location.href = 'inbox/approval_mdcr';
                      swalWithBootstrapButtons.fire('Thank You!','Response has been saved.','success')
                    }
                  },
                  error: function (response) {
                      Swal.fire({
                        position: 'center',
                        icon: 'info',
                        title: 'Oops! There\'s something wrong, <br>it might be slow network or expired user session.<br>Please refresh this page and try again.',
                        showConfirmButton: false,
                        timer: 1500
                      });
                  }
              });
          } else if (result.dismiss === Swal.DismissReason.cancel) {
              Swal.fire({
                position: 'center',
                icon: 'info',
                title: 'Action has been cancelled.',
                showConfirmButton: false,
                timer: 1500
              });
          }
      })

  }else{

    Swal.fire({
      position: 'center',
      icon: 'info',
      title: 'Aksi tidak dapat diproses',
      showConfirmButton: false,
      timer: 1500
    });

  }

}

function viewResumeNoReqToFI(noreq){
  
  var no_req = document.getElementById(noreq).getAttribute("id");

  window.open("inbox/mod_resume_no_req_to_fi/" + no_req, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=700,left=700,width=600,height=400");

}

function print_out_req_mdcr_at_resume(noreq){
  var no_req = document.getElementById(noreq).getAttribute("id");
  const d = new Date();
  var dY = d.getFullYear;
  var rand = 'MDCR'+(Math.floor(Math.random()*1000 + 1));
  window.open("form/print_out_req_mdcr/" + no_req + "/" + rand, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=700,left=700,width=600,height=600");

}

function print_out_req_mdcr_all_per_day(noreqMDCR){
  var no_req = document.getElementById(noreqMDCR).getAttribute("id");
  //alert(no_req);
  const d = new Date();
  var dY = d.getFullYear;
  var rand = 'MDCR'+(Math.floor(Math.random()*1000 + 1));
  window.open("form/print_out_req_mdcr_all_per_day/" + no_req + "/" + rand, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=700,left=700,width=600,height=600");

}