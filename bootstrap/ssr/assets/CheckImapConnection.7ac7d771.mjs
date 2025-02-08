import { mergeProps, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrInterpolate } from "vue/server-renderer";
import { _ as _export_sfc } from "./_plugin-vue_export-helper.43be4956.mjs";
const _sfc_main = {
  data() {
    return {
      textNotes: {
        "checkImapConnection": "Test Imap Connection",
        "checkImapConnectionProcess": "Connection Process ...",
        "imapError": "IMAP Account Id Is Empty"
      },
      message: "",
      messageType: "",
      loading: false
    };
  },
  props: {
    accountId: {
      type: String,
      require: true
    }
  },
  computed: {
    buttonText() {
      return this.loading ? this.textNotes.checkImapConnectionProcess : this.textNotes.checkImapConnection;
    }
  },
  methods: {
    async checkImap() {
      if (typeof this.accountId == "undefined") {
        this.message = this.textNotes.imapError;
        this.messageType = "error";
        return false;
      }
      this.message = "";
      this.loading = true;
      await axios.get("check_imap_connection/" + this.accountId).then((res) => {
        this.message = res.data.message;
        this.messageType = res.data.type;
      }).finally(() => this.loading = false);
      if (this.message != "") {
        this.$toast.open({
          message: this.message,
          type: this.messageType
        });
      }
    }
  }
};
function _sfc_ssrRender(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  _push(`<button${ssrRenderAttrs(mergeProps({
    disabled: $data.loading,
    class: "px-3 py-2 bg-blue-400 hover:bg-blue-600 text-white rounded-md font-semibold ml-3"
  }, _attrs))}>${ssrInterpolate($options.buttonText)}</button>`);
}
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/components/CheckImapConnection.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
const CheckImapConnection = /* @__PURE__ */ _export_sfc(_sfc_main, [["ssrRender", _sfc_ssrRender]]);
export {
  CheckImapConnection as default
};
