import { useSSRContext } from "vue";
const _sfc_main = {
  data() {
    return {
      count: 1
    };
  },
  props: ["email"],
  methods: {
    increase() {
      this.count++;
    },
    visitDashboard() {
      this.$splade.visit("/dashboard");
    }
  },
  mounted() {
  },
  render() {
    return this.$slots.default({
      count: this.count,
      increase: this.increase,
      email: this.email
    });
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/components/Counter.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
