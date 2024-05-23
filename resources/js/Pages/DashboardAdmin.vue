<template>
    <div class="container">
        <div class="row-card">
            <v-card class="card">
                <v-card-title>Total Categories</v-card-title>
                <v-card-text>{{ categoriesCount }}</v-card-text>
            </v-card>
        </div>

        <div class="row">
            <div id="barChart" class="chart-container"></div>
            <div id="pieChart" class="chart-container"></div>
        </div>
        <div class="row">
            <div id="piechart2" class="chart-container"></div>
            <div id="lineChart" class="chart-container"></div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import * as am4core from "@amcharts/amcharts4/core";
import * as am4charts from "@amcharts/amcharts4/charts";

export default {
    data() {
        return {
            categoriesCount: null,
            topContractModels: null,
        };
    },
    mounted() {
        this.getCategoriesCount();
        this.getTopContractModels();
        this.getModelsCountByCategory();
        this.getContractsCountBySampleUser();
        this.getGabaritUsageCount();
    },
    methods: {
        async getCategoriesCount() {
            try {
                const instance = axios.create({
                    baseURL: "http://localhost:8000/api",
                });
                const response = await instance.get("/admin/categories/count");
                this.categoriesCount = response.data;
            } catch (error) {
                console.error("Error fetching total admins:", error);
            }
        },
        async getTopContractModels() {
            try {
                const instance = axios.create({
                    baseURL: "http://localhost:8000/api",
                });
                const response = await instance.get(
                    "/admin/contract-models/top"
                );
                const topContractModels = response.data;
                topContractModels.sort(
                    (a, b) => a.contracts_count - b.contracts_count
                );
                this.updateChart(topContractModels);
            } catch (error) {
                console.error("Error fetching top 5 contract models:", error);
            }
        },
        updateChart(topContractModels) {
            var chart = am4core.create("barChart", am4charts.XYChart);
            chart.logo.disabled = true;
            chart.width = am4core.percent(100);
            chart.height = 400;
            chart.paddingLeft = 20;

            chart.data = topContractModels.map((model) => {
                return {
                    modelname: model.title,
                    modelcount: model.contracts_count,
                };
            });

            // Title
            var title = chart.titles.create();
            title.text = "Top 5 Contract Models";
            title.fontSize = 20;
            title.marginBottom = 25;
            // X-axis
            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "modelname";
            categoryAxis.title.text = "Contract Models";
            categoryAxis.title.fontWeight = "bold";
            categoryAxis.title.fontSize = 16;
            categoryAxis.renderer.labels.template.fontSize = 10;
            categoryAxis.renderer.labels.template.truncate = true;
            categoryAxis.renderer.labels.template.maxWidth = 110;
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 60;
            categoryAxis.renderer.cellStartLocation = 0.1;
            categoryAxis.renderer.cellEndLocation = 0.9;
            // Y-axis
            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.min = 0;
            // valueAxis.strictMinMax = true;
            valueAxis.numberFormatter = new am4core.NumberFormatter();
            valueAxis.numberFormatter.numberFormat = "#";
            // valueAxis.renderer.labels.template.adapter.add(
            //     "text",
            //     function (text) {
            //         return Math.round(text).toString();
            //     }
            // );

            valueAxis.title.text = "Number of Contracts";
            valueAxis.title.fontWeight = "bold";
            valueAxis.title.fontSize = 14;

            // Column series
            var series = chart.series.push(new am4charts.ColumnSeries());
            series.dataFields.categoryX = "modelname";
            series.dataFields.valueY = "modelcount";
            series.columns.template.strokeOpacity = 0;
            series.columns.template.column.cornerRadiusTopRight = 10;
            series.columns.template.column.cornerRadiusTopLeft = 10;
            series.columns.template.tooltipText =
                "{categoryX}: [bold]{valueY}[/b]";

            // Fill color
            series.columns.template.adapter.add(
                "fill",
                function (fill, target) {
                    return chart.colors.getIndex(target.dataItem.index);
                }
            );
            // Add bullets
            const bullet = series.bullets.push(new am4charts.Bullet());

            bullet.horizontalCenter = "middle";
            bullet.verticalCenter = "bottom";
            bullet.dy = 20;
            bullet.y = am4core.percent(100);
            bullet.propertyFields.href = "bullet";
        },

        async getModelsCountByCategory() {
            try {
                const instance = axios.create({
                    baseURL: "http://localhost:8000/api",
                });
                const response = await instance.get(
                    "/admin/categories/models/count"
                );
                const modelsCountByCategory = response.data;
                this.updatePieChart(modelsCountByCategory);
            } catch (error) {
                console.error(
                    "Error fetching models count by category:",
                    error
                );
            }
        },
        updatePieChart(modelsCountByCategory) {
            var pieChart = am4core.create("pieChart", am4charts.PieChart3D);
            pieChart.data = modelsCountByCategory;
            pieChart.logo.disabled = true;

            const title = pieChart.titles.create();
            title.text = "Contracts Generated by Each Sample User";
            title.fontSize = 20;
            title.fontFamily = "Arial";
            title.fill = am4core.color("#333");
            title.marginTop = 15;

            var pieSeries = pieChart.series.push(new am4charts.PieSeries3D());
            pieSeries.dataFields.value = "total_models";
            pieSeries.dataFields.category = "category_title";
            pieSeries.labels.template.disabled = true;
            pieSeries.ticks.template.disabled = true;
            pieSeries.slices.template.tooltipText =
                "[bold font-size: 12px]{category} ({value})";

            // legend
            pieChart.legend = new am4charts.Legend();
            pieChart.legend.useDefaultMarker = true;
            let marker = pieChart.legend.markers.template.children.getIndex(0);
            marker.cornerRadius(12, 12, 12, 12);
            pieChart.legend.markers.template.width = 14;
            pieChart.legend.markers.template.height = 14;
            pieChart.legend.valueLabels.template.disabled = true;
            pieChart.legend.labels.template.fill = am4core.color("#142559");
            pieChart.legend.labels.template.text =
                "[bold {#142559} font-size: 12px]{name}";
            pieChart.legend.position = "bottom";
            pieChart.legend.marginRight = 0;
            pieChart.legend.itemContainers.template.paddingTop = 10;
            pieChart.legend.itemContainers.template.paddingBottom = 10;
        },
        async getContractsCountBySampleUser() {
            try {
                const instance = axios.create({
                    baseURL: "http://localhost:8000/api",
                });
                const response = await instance.get("/admin/contracts/count");
                console.log(
                    "Contracts count by sample users response:",
                    response.data
                );

                const pieChartData = response.data.map((item) => ({
                    name: item.name,
                    total: item.total_contracts,
                }));

                const pieChart = am4core.create(
                    "piechart2",
                    am4charts.PieChart
                );
                pieChart.data = pieChartData;
                pieChart.logo.disabled = true;

                const title = pieChart.titles.create();
                title.text = "Contracts Generated by Each Sample User";
                title.fontSize = 20;
                title.fontFamily = "Arial";
                title.fill = am4core.color("#333");
                title.marginTop = 15;

                var pieSeries = pieChart.series.push(new am4charts.PieSeries());
                pieSeries.dataFields.value = "total";
                pieSeries.dataFields.category = "name";
                pieSeries.labels.template.disabled = true;
                pieSeries.ticks.template.disabled = true;
                pieSeries.slices.template.tooltipText =
                    "[bold font-size: 12px]{category} ({value})";

                pieChart.legend = new am4charts.Legend();
                pieChart.legend.useDefaultMarker = true;
                let marker =
                    pieChart.legend.markers.template.children.getIndex(0);
                marker.cornerRadius(12, 12, 12, 12);
                pieChart.legend.markers.template.width = 14;
                pieChart.legend.markers.template.height = 14;
                pieChart.legend.valueLabels.template.disabled = true;
                pieChart.legend.labels.template.fill = am4core.color("#142559");
                pieChart.legend.labels.template.text =
                    "[bold {#142559} font-size: 12px]{name}";
                pieChart.legend.position = "bottom";
                pieChart.legend.marginRight = 0;
                pieChart.legend.itemContainers.template.paddingTop = 10;
                pieChart.legend.itemContainers.template.paddingBottom = 10;
            } catch (error) {
                console.error(
                    "Error fetching contracts count by sample users:",
                    error
                );
            }
        },
        async getGabaritUsageCount() {
            try {
                const instance = axios.create({
                    baseURL: "http://localhost:8000/api",
                });
                const response = await instance.get(
                    "/admin/gabarits/usage-count"
                );
                const gabaritUsage = response.data;
                this.updateLineChart(gabaritUsage);
            } catch (error) {
                console.error("Error fetching gabarit usage count:", error);
            }
        },

        updateLineChart(gabaritUsage) {
            var chart = am4core.create("lineChart", am4charts.XYChart);
            chart.logo.disabled = true;

            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "gabarit_name";
            categoryAxis.renderer.minGridDistance = 30;
            categoryAxis.title.text = "Gabarits";
            categoryAxis.renderer.labels.template.fontSize = 9;
            categoryAxis.renderer.labels.template.truncate = true;
            categoryAxis.renderer.labels.template.maxWidth = 80;

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.title.text = " Usage Count";

            var series = chart.series.push(new am4charts.LineSeries());
            series.dataFields.categoryX = "gabarit_name";
            series.dataFields.valueY = "gabarit_usage_count";

            var bullet = series.bullets.push(new am4charts.CircleBullet());
            bullet.circle.radius = 4;
            bullet.fillOpacity = 0.8;
            bullet.strokeOpacity = 0;
            bullet.tooltipText = "{gabarit_name}:{valueY.value}";

            var title = chart.titles.create();
            title.text = "Gabarit Usage Count";
            title.fontSize = 20;
            title.marginBottom = 15;

            valueAxis.renderer.labels.template.adapter.add(
                "text",
                function (text) {
                    return Math.round(text).toString();
                }
            );

            var gabarits = gabaritUsage.map((gabarit) => ({
                gabarit_name: gabarit.title,
                gabarit_usage_count: gabarit.contract_models_count,
            }));

            chart.data = gabarits;
        },
    },
};
</script>
<style scoped>
.container {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
    background-color: #f2f2f2;
}

.card {
    width: 100vh;
    margin-bottom: 15px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.row {
    display: flex;
    justify-content: space-between;
    width: 100%;
    margin-bottom: 10px;
}
.chart-container {
    width: 70%;
    height: 400px;
    background-color: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    border: 1px solid #ddd;
    margin-right: 5px;
}
</style>
