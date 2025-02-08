import { JoditEditor } from "jodit-vue";
import { mergeProps, useSSRContext, resolveComponent, withCtx, createVNode, toDisplayString, withModifiers, withDirectives, vModelText } from "vue";
import { ssrRenderAttrs, ssrRenderClass, ssrInterpolate, ssrRenderSlot, ssrRenderList, ssrRenderStyle, ssrRenderComponent, ssrRenderAttr, ssrIncludeBooleanAttr } from "vue/server-renderer";
import { _ as _export_sfc } from "./_plugin-vue_export-helper.43be4956.mjs";
const jodit_min = "";
const _sfc_main$1 = {
  data() {
    return {
      open: false
    };
  },
  props: {
    placement: {
      type: String,
      default: "right",
      validator: (value) => ["right", "left"].indexOf(value) !== -1
    },
    options: {
      type: Array,
      require: true
    },
    dropdownTitle: {
      type: String,
      require: true
    },
    chooseOption: {
      type: Function,
      require: true
    }
  },
  mounted() {
  }
};
function _sfc_ssrRender$1(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  _push(`<div${ssrRenderAttrs(mergeProps({ class: "relative" }, _attrs))}><button type="button" class="${ssrRenderClass(["float-" + $props.placement, "z-10 relative flex items-center focus:outline-none select-none"])}"><span class="px-2 py-2 border rounded inline-flex items-center text-sm"><span class="mr-2">${ssrInterpolate($props.dropdownTitle)}</span><svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M4.516 7.548c.436-.446 1.043-.481 1.576 0L10 11.295l3.908-3.747c.533-.481 1.141-.446 1.574 0 .436.445.408 1.197 0 1.615-.406.418-4.695 4.502-4.695 4.502a1.095 1.095 0 0 1-1.576 0S4.924 9.581 4.516 9.163c-.409-.418-.436-1.17 0-1.615z"></path></svg></span></button>`);
  if ($data.open) {
    _push(`<button class="fixed inset-0 h-full w-full cursor-default focus:outline-none" tabindex="-1"></button>`);
  } else {
    _push(`<!---->`);
  }
  if ($data.open) {
    _push(`<div class="${ssrRenderClass([$props.placement === "right" ? "right-0" : "left-0", "hidden md:block absolute z-10 shadow-lg border w-full rounded py-1 px-2 text-sm mt-2 bg-white"])}">`);
    ssrRenderSlot(_ctx.$slots, "content", {}, () => {
      _push(`<!--[-->`);
      ssrRenderList($props.options, (option, index) => {
        _push(`<span class="flex w-full justify-between items-center rounded px-2 py-1 my-1 hover:bg-indigo-600 hover:text-white">${ssrInterpolate(index)}</span>`);
      });
      _push(`<!--]-->`);
    }, _push, _parent);
    _push(`</div>`);
  } else {
    _push(`<!---->`);
  }
  if ($data.open) {
    _push(`<div class="md:hidden fixed inset-x-0 z-10 bottom-0 bg-white w-full z-20 px-2 py-2 shadow-2xl leading-loose">`);
    ssrRenderSlot(_ctx.$slots, "content", {}, () => {
      _push(`<!--[-->`);
      ssrRenderList($props.options, (option, index) => {
        _push(`<span class="flex w-full justify-between items-center rounded px-2 py-1 my-1 hover:bg-indigo-600 hover:text-white">${ssrInterpolate(index)}</span>`);
      });
      _push(`<!--]-->`);
    }, _push, _parent);
    _push(`</div>`);
  } else {
    _push(`<!---->`);
  }
  if ($data.open) {
    _push(`<div class="md:hidden fixed w-full h-full inset-0 bg-gray-900 opacity-50 z-10"></div>`);
  } else {
    _push(`<!---->`);
  }
  _push(`</div>`);
}
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/components/Vdropdown.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const Vdropdown = /* @__PURE__ */ _export_sfc(_sfc_main$1, [["ssrRender", _sfc_ssrRender$1]]);
const SendEmail_vue_vue_type_style_index_0_scoped_238ad82b_lang = "";
const _sfc_main = {
  components: {
    JoditEditor,
    Vdropdown
  },
  data() {
    return {
      textNotes: {
        "SmtpError": "Please Choose Account",
        "EmailTo": "Email To",
        "EmailCc": "Email Cc",
        "Content": "Content",
        "Subject": "Subject",
        "Choose_Template": "Choose Template",
        "Upload_Files": "Upload Files",
        "Send": "Send",
        "EmailToEmpty": "Email To Can not be empty",
        "SubjectEmpty": "Subject Can not be empty",
        "ContentEmpty": "Content Can not be empty",
        "NewMessage": "New Message",
        "Reply": "Reply"
      },
      message: "",
      title: "Send New Email",
      messageType: "",
      loading: false,
      showModal: false,
      dropdownTitle: "Choose Template",
      bodyFormData: new FormData(),
      formData: {
        EmailCc: "",
        EmailTo: "",
        Content: "",
        Subject: "",
        Files: ""
      }
    };
  },
  props: {
    sendTo: {
      type: String,
      require: true
    },
    emailData: {
      type: Array,
      require: true
    },
    templates: {
      type: Array,
      require: true
    },
    openModal: {
      type: String,
      default: false
    },
    canBeReplied: {
      type: String,
      default: false
    },
    accountId: {
      type: String,
      require: true
    }
  },
  mounted() {
    if (this.openModal) {
      this.showModal = true;
    } else {
      this.showModal = false;
    }
    if (this.sendTo != "" && this.openModal) {
      this.formData.EmailTo = this.sendTo[0];
    }
    if (typeof this.templates != "undefined") {
      this.templates.Clear = "";
    }
  },
  computed: {
    maxWidth() {
      switch (this.width) {
        case "xs":
          return "max-w-lg";
        case "sm":
          return "max-w-xl";
        case "md":
          return "max-w-2xl";
        case "lg":
          return "max-w-3xl";
        case "full":
          return "max-w-full";
      }
    },
    shade() {
      switch (this.type) {
        case "info":
          return "gray";
        case "warning":
          return "yellow";
        case "success":
          return "teal";
        case "danger":
          return "red";
      }
    },
    color() {
      return `text-${this.shade}-600`;
    }
  },
  methods: {
    setReplyContent(val) {
      return ' <br><br><br><blockquote style="border-left:1px solid #0857A6; margin:10px; padding:0 0 0 10px;">' + val + " </blockquote>";
    },
    uploadFiles(e) {
      this.formData.Files = e.target.files;
    },
    visitDashboard() {
      this.$splade.visit("/dashboard");
    },
    async submitForm() {
      this.message = "";
      this.loading = true;
      if (this.accountId == "") {
        this.message = this.textNotes.SmtpError;
        this.messageType = "error";
      }
      if (this.formData.EmailTo == "") {
        this.message = this.textNotes.EmailToEmpty;
        this.messageType = "error";
      }
      if (this.formData.Subject == "") {
        this.message = this.textNotes.SubjectEmpty;
        this.messageType = "error";
      }
      if (this.formData.Content == "") {
        this.message = this.textNotes.ContentEmpty;
        this.messageType = "error";
      }
      if (this.message != "") {
        this.$toast.open({
          message: this.message,
          type: this.messageType
        });
        return false;
      }
      this.message = "";
      this.loading = true;
      this.bodyFormData.append("mail_to", this.formData.EmailTo);
      this.bodyFormData.append("mail_cc", this.formData.EmailCc);
      this.bodyFormData.append("subject", this.formData.Subject);
      this.bodyFormData.append("body", this.formData.Content);
      for (var x = 0; x < this.formData.Files.length; x++) {
        this.bodyFormData.append("attachment[]", this.formData.Files[x]);
      }
      await axios({
        method: "post",
        url: "/send_email",
        data: this.bodyFormData,
        headers: { "Content-Type": "multipart/form-data" }
      }).then((res) => {
        this.message = res.data.message;
        this.messageType = res.data.type;
      }).catch((error) => {
        this.messageType = "error";
        this.message = error.response.data.message;
      }).finally(() => {
        this.loading = false;
        if (this.message != "") {
          this.showModal = false;
          this.$toast.open({
            message: this.message,
            type: this.messageType
          });
        }
        this.bodyFormData = new FormData();
      });
    },
    chooseOption(val) {
      this.formData.Content = this.templates[val];
    },
    openNewMessageModal() {
      this.showModal = true;
      this.formData = {
        EmailCc: "",
        EmailTo: "",
        Content: "",
        Subject: "",
        Files: ""
      };
    },
    openReplyMessageModal() {
      this.formData.Files = "";
      if (this.sendTo != "") {
        this.formData.EmailTo = this.sendTo[0];
      }
      if (this.emailData.cc != "") {
        this.formData.EmailCc = this.emailData.cc;
      }
      if (this.emailData.subject != "") {
        this.formData.Subject = this.emailData.subject;
      }
      if (this.emailData.body != "") {
        this.formData.Content = this.setReplyContent(this.emailData.body);
      }
      this.showModal = true;
    }
  }
};
function _sfc_ssrRender(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_v_modal = resolveComponent("v-modal");
  const _component_Vdropdown = resolveComponent("Vdropdown");
  const _component_jodit_editor = resolveComponent("jodit-editor");
  _push(`<!--[--><button style="${ssrRenderStyle(!$data.showModal && $props.accountId ? null : { display: "none" })}" class="z-20 text-black flex flex-col shrink-0 grow-0 justify-around fixed bottom-0 right-0 rounded-lg mr-1 mb-5 lg:mr-5 lg:mb-5 xl:mr-10 xl:mb-10 px-3 py-2 bg-gray-400 hover:bg-gray-600 text-white rounded-md font-semibold" data-v-238ad82b>${ssrInterpolate($data.textNotes.NewMessage)}</button><button style="${ssrRenderStyle(!$data.showModal && $props.canBeReplied && $props.accountId ? null : { display: "none" })}" class="z-20 text-black flex flex-col shrink-0 grow-0 justify-around fixed bottom-0 right-150 rounded-lg mr-1 mb-5 lg:mr-5 lg:mb-5 xl:mr-10 xl:mb-10 px-3 py-2 bg-gray-400 hover:bg-gray-600 text-white rounded-md font-semibold" data-v-238ad82b>${ssrInterpolate($data.textNotes.Reply)}</button>`);
  if ($data.showModal) {
    _push(ssrRenderComponent(_component_v_modal, {
      title: "Confirm Action",
      width: "sm",
      onClose: ($event) => $data.showModal = false
    }, {
      default: withCtx((_, _push2, _parent2, _scopeId) => {
        if (_push2) {
          _push2(`<div class="fixed w-full h-full top-0 left-0 flex items-center justify-center z-10" data-v-238ad82b${_scopeId}><div class="absolute w-full h-full bg-gray-900 opacity-50" data-v-238ad82b${_scopeId}></div><div class="${ssrRenderClass([$options.maxWidth, "absolute max-h-full"])}" data-v-238ad82b${_scopeId}><div class="container bg-white overflow-hidden md:rounded" data-v-238ad82b${_scopeId}><div class="px-4 py-4 leading-none flex justify-between items-center font-medium text-sm bg-gray-100 border-b select-none" data-v-238ad82b${_scopeId}><h3 data-v-238ad82b${_scopeId}>${ssrInterpolate($data.title)}</h3><div class="text-2xl hover:text-gray-600 cursor-pointer" data-v-238ad82b${_scopeId}> \xD7 </div></div><div class="max-height-700 px-4" data-v-238ad82b${_scopeId}><div class="container mx-auto py-2 px-4" data-v-238ad82b${_scopeId}><form class="max-w-4xl mx-auto bg-white rounded-lg" data-v-238ad82b${_scopeId}><div class="mt-3 mb-3 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6" data-v-238ad82b${_scopeId}><div class="sm:col-span-3" data-v-238ad82b${_scopeId}><label for="to" class="block text-gray-700 mb-2" data-v-238ad82b${_scopeId}>${ssrInterpolate($data.textNotes.EmailTo)}</label><input${ssrRenderAttr("value", $data.formData.EmailTo)} type="text" id="to" name="mail_to" class="w-full form-input p-1 border rounded-md shadow-sm" required data-v-238ad82b${_scopeId}></div><div class="sm:col-span-3" data-v-238ad82b${_scopeId}><label for="cc" class="block text-gray-700 mb-2" data-v-238ad82b${_scopeId}>${ssrInterpolate($data.textNotes.EmailCc)}</label><input${ssrRenderAttr("value", $data.formData.EmailCc)} type="text" id="cc" name="cc" class="w-full form-input p-1 border rounded-md shadow-sm" data-v-238ad82b${_scopeId}></div><div class="sm:col-span-6" data-v-238ad82b${_scopeId}><label for="subject" class="block text-gray-700 mb-2" data-v-238ad82b${_scopeId}>${ssrInterpolate($data.textNotes.Subject)}</label><input${ssrRenderAttr("value", $data.formData.Subject)} type="text" id="subject" name="subject" class="w-full form-input p-1 border rounded-md shadow-sm" required data-v-238ad82b${_scopeId}></div><div class="sm:col-span-3" data-v-238ad82b${_scopeId}><label class="block text-gray-700 mb-2" data-v-238ad82b${_scopeId}>${ssrInterpolate($data.textNotes.Choose_Template)}</label>`);
          _push2(ssrRenderComponent(_component_Vdropdown, {
            placement: "left",
            chooseOption: $options.chooseOption,
            dropdownTitle: $data.dropdownTitle,
            options: $props.templates
          }, {
            button: withCtx((_2, _push3, _parent3, _scopeId2) => {
              if (_push3)
                ;
              else {
                return [];
              }
            }),
            content: withCtx((_2, _push3, _parent3, _scopeId2) => {
              if (_push3)
                ;
              else {
                return [];
              }
            }),
            _: 1
          }, _parent2, _scopeId));
          _push2(`</div><div class="sm:col-span-3" data-v-238ad82b${_scopeId}><label class="block text-gray-700 mb-2" data-v-238ad82b${_scopeId}>${ssrInterpolate($data.textNotes.Upload_Files)}</label><div data-v-238ad82b${_scopeId}><input type="file" class="w-full p-1" multiple data-v-238ad82b${_scopeId}></div></div><div class="sm:col-span-6" data-v-238ad82b${_scopeId}><label for="address" class="block text-gray-700 mb-2" data-v-238ad82b${_scopeId}>${ssrInterpolate($data.textNotes.Content)}</label>`);
          _push2(ssrRenderComponent(_component_jodit_editor, {
            name: "body",
            modelValue: $data.formData.Content,
            "onUpdate:modelValue": ($event) => $data.formData.Content = $event
          }, null, _parent2, _scopeId));
          _push2(`</div></div><button type="submit"${ssrIncludeBooleanAttr($data.loading) ? " disabled" : ""} class="px-3 py-2 bg-green-400 hover:bg-green-600 text-white rounded-md font-semibold" data-v-238ad82b${_scopeId}>${ssrInterpolate($data.textNotes.Send)}</button></form></div></div></div></div></div>`);
        } else {
          return [
            createVNode("div", { class: "fixed w-full h-full top-0 left-0 flex items-center justify-center z-10" }, [
              createVNode("div", {
                class: "absolute w-full h-full bg-gray-900 opacity-50",
                onClick: ($event) => $data.showModal = false
              }, null, 8, ["onClick"]),
              createVNode("div", {
                class: ["absolute max-h-full", $options.maxWidth]
              }, [
                createVNode("div", { class: "container bg-white overflow-hidden md:rounded" }, [
                  createVNode("div", { class: "px-4 py-4 leading-none flex justify-between items-center font-medium text-sm bg-gray-100 border-b select-none" }, [
                    createVNode("h3", null, toDisplayString($data.title), 1),
                    createVNode("div", {
                      onClick: ($event) => $data.showModal = false,
                      class: "text-2xl hover:text-gray-600 cursor-pointer"
                    }, " \xD7 ", 8, ["onClick"])
                  ]),
                  createVNode("div", { class: "max-height-700 px-4" }, [
                    createVNode("div", { class: "container mx-auto py-2 px-4" }, [
                      createVNode("form", {
                        onSubmit: withModifiers($options.submitForm, ["prevent"]),
                        class: "max-w-4xl mx-auto bg-white rounded-lg"
                      }, [
                        createVNode("div", { class: "mt-3 mb-3 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6" }, [
                          createVNode("div", { class: "sm:col-span-3" }, [
                            createVNode("label", {
                              for: "to",
                              class: "block text-gray-700 mb-2"
                            }, toDisplayString($data.textNotes.EmailTo), 1),
                            withDirectives(createVNode("input", {
                              "onUpdate:modelValue": ($event) => $data.formData.EmailTo = $event,
                              type: "text",
                              id: "to",
                              name: "mail_to",
                              class: "w-full form-input p-1 border rounded-md shadow-sm",
                              required: ""
                            }, null, 8, ["onUpdate:modelValue"]), [
                              [vModelText, $data.formData.EmailTo]
                            ])
                          ]),
                          createVNode("div", { class: "sm:col-span-3" }, [
                            createVNode("label", {
                              for: "cc",
                              class: "block text-gray-700 mb-2"
                            }, toDisplayString($data.textNotes.EmailCc), 1),
                            withDirectives(createVNode("input", {
                              "onUpdate:modelValue": ($event) => $data.formData.EmailCc = $event,
                              type: "text",
                              id: "cc",
                              name: "cc",
                              class: "w-full form-input p-1 border rounded-md shadow-sm"
                            }, null, 8, ["onUpdate:modelValue"]), [
                              [vModelText, $data.formData.EmailCc]
                            ])
                          ]),
                          createVNode("div", { class: "sm:col-span-6" }, [
                            createVNode("label", {
                              for: "subject",
                              class: "block text-gray-700 mb-2"
                            }, toDisplayString($data.textNotes.Subject), 1),
                            withDirectives(createVNode("input", {
                              "onUpdate:modelValue": ($event) => $data.formData.Subject = $event,
                              type: "text",
                              id: "subject",
                              name: "subject",
                              class: "w-full form-input p-1 border rounded-md shadow-sm",
                              required: ""
                            }, null, 8, ["onUpdate:modelValue"]), [
                              [vModelText, $data.formData.Subject]
                            ])
                          ]),
                          createVNode("div", { class: "sm:col-span-3" }, [
                            createVNode("label", { class: "block text-gray-700 mb-2" }, toDisplayString($data.textNotes.Choose_Template), 1),
                            createVNode(_component_Vdropdown, {
                              placement: "left",
                              chooseOption: $options.chooseOption,
                              dropdownTitle: $data.dropdownTitle,
                              options: $props.templates
                            }, {
                              button: withCtx(() => []),
                              content: withCtx(() => []),
                              _: 1
                            }, 8, ["chooseOption", "dropdownTitle", "options"])
                          ]),
                          createVNode("div", { class: "sm:col-span-3" }, [
                            createVNode("label", { class: "block text-gray-700 mb-2" }, toDisplayString($data.textNotes.Upload_Files), 1),
                            createVNode("div", null, [
                              createVNode("input", {
                                type: "file",
                                class: "w-full p-1",
                                multiple: "",
                                onChange: $options.uploadFiles
                              }, null, 40, ["onChange"])
                            ])
                          ]),
                          createVNode("div", { class: "sm:col-span-6" }, [
                            createVNode("label", {
                              for: "address",
                              class: "block text-gray-700 mb-2"
                            }, toDisplayString($data.textNotes.Content), 1),
                            createVNode(_component_jodit_editor, {
                              name: "body",
                              modelValue: $data.formData.Content,
                              "onUpdate:modelValue": ($event) => $data.formData.Content = $event
                            }, null, 8, ["modelValue", "onUpdate:modelValue"])
                          ])
                        ]),
                        createVNode("button", {
                          type: "submit",
                          disabled: $data.loading,
                          class: "px-3 py-2 bg-green-400 hover:bg-green-600 text-white rounded-md font-semibold"
                        }, toDisplayString($data.textNotes.Send), 9, ["disabled"])
                      ], 40, ["onSubmit"])
                    ])
                  ])
                ])
              ], 2)
            ])
          ];
        }
      }),
      _: 1
    }, _parent));
  } else {
    _push(`<!---->`);
  }
  _push(`<!--]-->`);
}
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/components/SendEmail.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
const SendEmail = /* @__PURE__ */ _export_sfc(_sfc_main, [["ssrRender", _sfc_ssrRender], ["__scopeId", "data-v-238ad82b"]]);
export {
  SendEmail as default
};
