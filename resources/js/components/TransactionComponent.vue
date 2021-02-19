<template>
  <v-app>
    <v-container class="mt-4">
      <v-tabs color="indigo" show-arrows>
        <v-tab>Agregar Transacción</v-tab>
        <v-tab>Registro de Transacciones</v-tab>
        <v-tab-item>
          <v-container fluid>
            <h1 class="my-3">😸 😺 🐒</h1>
            <v-form ref="form" v-model="valid" lazy-validation>
              <v-row>
                <v-col md="4" cols="12">
                  <v-text-field
                    label="Monto"
                    prefix="$"
                    v-model="transaction.amount"
                    :rules="doubleRules"
                  >
                  </v-text-field>
                </v-col>
                <v-col>
                  <v-text-field
                    label="Descripción"
                    v-model="transaction.description"
                  >
                  </v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col>
                  <v-btn
                    color="error"
                    class="mr-3"
                    @click="storeTransaction(true)"
                  >
                    <v-icon dark> mdi-minus </v-icon>Egreso
                  </v-btn>
                  <v-btn
                    color="teal darken-2"
                    dark
                    @click="storeTransaction(false)"
                  >
                    <v-icon dark> mdi-plus </v-icon>Ingreso
                  </v-btn>
                </v-col>
              </v-row>
            </v-form>
          </v-container>
        </v-tab-item>
        <v-tab-item>
          <v-container fluid>
            <h1
              class="red--text"
              v-bind:class="[
                total > 0
                  ? 'teal--text text--darken-1'
                  : 'deep-orange--text text--lighten-2',
              ]"
            >
              $ {{ total }}
            </h1>
            <v-row>
              <v-col cols="12" md="4">
                <v-timeline align-top dense>
                  <v-timeline-item
                    v-for="item in items"
                    :key="item.id"
                    :color="item.color"
                  >
                    <v-card :color="item.color" dark>
                      <v-card-title class="title">
                        {{ item.created }}
                      </v-card-title>
                      <v-card-text class="white text--primary">
                        <h3>$ {{ item.amount }}</h3>
                        <h5>{{ item.description }}</h5>
                        <v-btn
                          :color="item.color"
                          class="mx-0"
                          outlined
                          @click="deleteTransaction(item)"
                        >
                          <v-icon dark> mdi-delete </v-icon>
                        </v-btn>
                      </v-card-text>
                    </v-card>
                  </v-timeline-item>
                </v-timeline>
              </v-col>
              <v-col>
                <h3>Listado Egresos</h3>
                <v-card v-if="negativeTransaction.length > 0">
                  <v-list color="blue-grey lighten-5">
                    <template v-for="item in negativeTransaction">
                      <v-list-item :key="item.id">
                        <v-list-item-content>
                          <v-list-item-title>
                            $ {{ item.amount }}
                            <p>{{ item.description }}</p>
                          </v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                    </template>
                  </v-list>
                </v-card>
                <p v-else>Sin registro.</p>
              </v-col>
              <v-col>
                <h3>Listado Ingresos</h3>
                <v-card v-if="positiveTransaction.length > 0">
                  <v-list color="blue-grey lighten-5">
                    <template v-for="item in positiveTransaction">
                      <v-list-item :key="item.id">
                        <v-list-item-content>
                          <v-list-item-title>
                            $ {{ item.amount }}
                            <p>{{ item.description }}</p>
                          </v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                    </template>
                  </v-list>
                </v-card>
                <p v-else>Sin registro.</p>
              </v-col>
            </v-row>
          </v-container>
        </v-tab-item>
      </v-tabs>
    </v-container>
    <v-snackbar
      v-model="snackbar.show"
      timeout="4500"
      :color="snackbar.color"
      dark
      left
    >
      {{ snackbar.message }}
      <template v-slot:action="{ attrs }">
        <v-btn text v-bind="attrs" @click="snackbar.show = false">
          Cerrar
        </v-btn>
      </template>
    </v-snackbar>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      valid: true,

      config: {
        headers: {
          Authorization: "Bearer " + window.Laravel.api_token,
          Accept: "application/json",
        },
      },

      total: 0,
      transaction: {
        amount: "",
        description: "",
      },

      doubleRules: [
        (v) => !!v || "Este campo es requerido.",
        (v) => (+v && v > 0) || "Deben ser valor mayor que 0.",
      ],

      snackbar: {
        show: false,
        message: "",
        color: "",
      },
      items: [],
    };
  },

  mounted() {
    this.setTransaction();
  },

  computed: {
    positiveTransaction() {
      return this.items.filter((item) => item.amount >= 0);
    },

    negativeTransaction() {
      return this.items.filter((item) => item.amount < 0);
    },
  },

  methods: {
    setTransaction() {
      axios
        .get("api/transaction", this.config)
        .then(({ data }) => {
          this.total = data.total;
          this.items = data.records;
        })
        .catch((err) => {
          console.log(err);
          this.snackbar = {
            show: true,
            message: "Error inesperado al obtener los registros.",
            color: "red",
          };
        });
    },

    storeTransaction(isNegative) {
      if (+this.transaction.amount && this.valid) {
        let amount = this.transaction.amount;
        if (isNegative) {
          amount = amount * -1;
        }
        axios
          .post("api/transaction", { ...this.transaction, amount }, this.config)
          .then(({ data }) => {
            console.log(data.message);
            this.snackbar = {
              show: true,
              message: data.message,
              color: "indigo",
            };
            this.cleanForm();
            this.setTransaction();
          })
          .catch((err) => {
            console.log(err);
            this.snackbar = {
              show: true,
              message: "Error inesperado.",
              color: "red",
            };
          });
      } else {
        this.snackbar = {
          show: true,
          message: "Debe completar el formulario.",
          color: "red",
        };
      }
    },

    deleteTransaction({ id }) {
      axios
        .delete("api/transaction", { ...this.config, params: { id } })
        .then(({ data }) => {
          this.setTransaction();
          this.snackbar = {
            show: true,
            message: data.message,
            color: "indigo",
          };
        })
        .catch((err) => {
          console.log(err);
          this.snackbar = {
            show: true,
            message: "Error inesperado.",
            color: "red",
          };
        });
    },

    cleanForm() {
      this.$refs.form.reset();
      this.transaction = { amount: "", description: "" };
    },
  },
};
</script>
