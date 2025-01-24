<script>
export default {
    data() {
        return {
            count: 1
        };
    },
    props: ['email'],
    methods: {
        increase() {
            this.count++;
        },
        visitDashboard() {
            this.$splade.visit("/dashboard");
        },
    },
    mounted() {

    },
    render() {

        return this.$slots.default({
            count: this.count,
            increase: this.increase,
            email: this.email,
        });
    },
};
</script>


