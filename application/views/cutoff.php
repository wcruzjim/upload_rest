<div class="newPage">
    <div class="w-100 title-cutoff"><?php echo $name?></div>
    <div class="imageContainer">
        <img src="<?php echo $timeArea?>" alt="image" class="w-100 timeArea">
    </div>
    <div class="imageContainer">
        <img src="<?php echo $details?>" alt="image" class="w-100 details">
    </div>
    <div class="imageContainer">
        <img src="<?php echo $intervalFollowUp?>" alt="image" class="w-100 intervalFollowUp">
    </div>
    <div class="imageContainer">
        <img src="<?php echo $pareto?>" alt="image" class="w-100 pareto">
    </div>
    <div class="imageContainer">
        <img src="<?php echo $summaryIndicators?>" alt="image" class="w-100 summaryIndicators">
    </div>
    <div class="imageContainer">
        <img src="<?php echo $justifications?>" alt="image" class="w-100 justification">
    </div>
</div>



<style>
    @page {
        margin-top: 42mm;
        margin-right: 12mm;
        margin-bottom: 30mm;
        margin-left: 12mm;
    }
    .title-cutoff{
        text-align: center;
        font-size: 17px;
        font-weight: bolder;
    }

    .imageContainer {
        text-align: center;
        margin-top: 10px;
        margin-bottom: 15px;
    }

    .w-100{
        width: 100%;
    }

    .timeArea {
        height: 200px;
    }

    .summaryIndicators {
        height: 100px;
    }

    .intervalFollowUp {
        height: 350px;
    }

    .pareto {
        height: 200px;
    }

    .detail {
        height: 200px;
    }

    .justification {
        height: 300px;
    }

    .footer1 {
        position: fixed;
        top: 20mm;
        left: 10mm;
        z-index: 999;
    }

    .newPage {
        display: inline-block;
        width: 100%;
        font-family: Arial,
            sans-serif;
        font-size: 12px;
        text-align: justify;
    }
</style>