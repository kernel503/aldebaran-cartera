<template>
  <v-app>
    <v-container class="mt-4">
      <v-tabs color="indigo">
        <v-tab>Agregar Transacción</v-tab>
        <v-tab>Registro de Transacciones</v-tab>
        <v-tab-item>
          <v-container fluid>
            <v-row>
              <v-col md="4" cols="12">
                <v-text-field
                  label="Monto"
                  prefix="$"
                  v-model="transaction.amount"
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
                <v-btn color="error" class="mr-3">
                  <v-icon dark> mdi-minus </v-icon>Egreso
                </v-btn>
                <v-btn color="teal darken-2" dark>
                  <v-icon dark> mdi-plus </v-icon>Ingreso
                </v-btn>
              </v-col>
            </v-row>
          </v-container>
        </v-tab-item>
        <v-tab-item>
          <v-container fluid>
            <v-row>
              <v-col cols="12" md="8">
                <v-timeline align-top>
                  <v-timeline-item
                    v-for="(item, i) in items"
                    :key="i"
                    :color="item.color"
                  >
                    <v-card :color="item.color" dark>
                      <v-card-title class="title">
                        Lorem Ipsum Dolor
                      </v-card-title>
                      <v-card-text class="white text--primary">
                        <p>monto</p>
                        <p>
                          Lorem ipsum dolor sit amet, no nam oblique veritus.
                          Commune scaevola imperdiet nec ut, sed euismod
                          convenire principes at. Est et nobis iisque percipit,
                          an vim zril disputando voluptatibus, vix an salutandi
                          sententiae.
                        </p>
                        <v-btn :color="item.color" class="mx-0" outlined>
                          <v-icon dark> mdi-delete </v-icon>
                        </v-btn>
                      </v-card-text>
                    </v-card>
                  </v-timeline-item>
                </v-timeline>
              </v-col>
            </v-row>
          </v-container>
        </v-tab-item>
      </v-tabs>
    </v-container>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      transaction: {
        amount: 0,
        description: "",
      },
      items: [
        {
          color: "red lighten-2",
          icon: "mdi-star",
        },
        {
          color: "purple darken-1",
          icon: "mdi-book-variant",
        },
        {
          color: "green lighten-1",
          icon: "mdi-airballoon",
        },
        {
          color: "indigo",
          icon: "mdi-buffer",
        },
      ],
    };
  },
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
