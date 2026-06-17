<template>
  <div class="payment-container">
    <h2>Stripe Payment</h2>

    <!-- IMPORTANT: DO NOT hide card element with v-if -->
    <div v-show="!loading">
      <div ref="cardElement" class="card-box"></div>

      <button @click="pay" :disabled="paying">
        {{ paying ? "Paying..." : "Pay Now" }}
      </button>

      <p v-if="error" class="error">{{ error }}</p>
      <p v-if="success" class="success">Payment successful 🎉</p>
    </div>

    <div v-if="loading">Loading payment form...</div>
  </div>
</template>

<script>
import { loadStripe } from "@stripe/stripe-js";
import api from "../services/api";

const stripePromise = loadStripe("pk_test_51PAKmTGJFiD7APbfGcowgkEk8vzDrF7QrC48mqLALkc0TcdF5Xamq5u3SoJnocEOIs72jzxNpeWnOps9eRBWH4N800T8kIpP8O");

export default {
  name: "StripePayment",

  data() {
    return {
      stripe: null,
      elements: null,
      cardElement: null,
      loading: true,
      paying: false,
      error: null,
      success: false,
      mountedReady: false,
    };
  },

  async mounted() {
    // STEP 1: ensure DOM is rendered first
    await this.$nextTick();
    this.mountedReady = true;

    // STEP 2: init stripe AFTER DOM exists
    this.initStripe();
  },

  methods: {
    async initStripe() {
      try {
        this.stripe = await stripePromise;

        this.elements = this.stripe.elements();
        this.cardElement = this.elements.create("card");

        // IMPORTANT CHECK
        if (!this.$refs.cardElement) {
          throw new Error("Card element ref is missing");
        }

        this.cardElement.mount(this.$refs.cardElement);

        this.loading = false;
      } catch (err) {
        console.error(err);
        this.error = "Stripe init failed";
        this.loading = false;
      }
    },

    async pay() {
      this.paying = true;
      this.error = null;
      this.success = false;

      try {
        const res = await api.post("/payment/create-intent", {
          amount: 1000,
        });

        const clientSecret = res.data.clientSecret;

        const result = await this.stripe.confirmCardPayment(
          clientSecret,
          {
            payment_method: {
              card: this.cardElement,
            },
          }
        );

        if (result.error) {
          this.error = result.error.message;
        } else if (result.paymentIntent.status === "succeeded") {
          this.success = true;
        }
      } catch (err) {
        console.log(err)
        this.error = "Payment failed";
      } finally {
        this.paying = false;
      }
    },
  },
};
</script>

<style scoped>
.payment-container {
  max-width: 400px;
  margin: 50px auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 8px;
}

.card-box {
  padding: 12px;
  border: 1px solid #ccc;
  margin-bottom: 15px;
  border-radius: 6px;
  min-height: 40px;
}

button {
  width: 100%;
  padding: 10px;
  background: #635bff;
  color: white;
  border: none;
  border-radius: 6px;
}

button:disabled {
  background: #aaa;
}

.error {
  color: red;
}

.success {
  color: green;
}
</style>