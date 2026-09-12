<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JournalPageSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('journal_pages')->insert([
            array (
  'id' => 1,
  'title' => 'Editorial Board Member',
  'slug' => 'editorial-board-member',
  'menu_group' => 'editorial_board',
  'content' => '<!-- ============================================================
     BMRC JOURNAL - EDITORIAL BOARD
     PROFESSIONAL RESPONSIVE PRESENTATION
============================================================= -->

<div class="bmrc-editorial-board">

    <!-- ========================================================
         INTRODUCTION
    ========================================================= -->

    <div class="text-center mb-5">

        <div
            class="d-inline-flex align-items-center px-3 py-2 rounded-pill mb-3"
            style="
                background:#eaf4f7;
                color:#0b5563;
                font-size:13px;
                font-weight:700;
                letter-spacing:.08em;
                text-transform:uppercase;
            "
        >
            Bangladesh Medical Research Council Journal
        </div>

        <h2
            class="fw-bold mb-3"
            style="
                color:#123b4a;
                font-size:clamp(30px, 4vw, 44px);
            "
        >
            Editorial Board
        </h2>

        <div
            style="
                width:72px;
                height:4px;
                margin:0 auto 18px;
                background:#0d6b7b;
                border-radius:10px;
            "
        ></div>

        <p
            class="mx-auto mb-2"
            style="
                max-width:760px;
                color:#5f7280;
                font-size:16px;
                line-height:1.7;
            "
        >
            The Editorial Board brings together experienced
            academicians, clinicians, researchers and scientific
            professionals who contribute to the academic quality,
            editorial integrity and scholarly standards of the journal.
        </p>

        <p
            class="mb-0"
            style="
                color:#7a8b95;
                font-size:14px;
                font-style:italic;
            "
        >
            Listed not according to seniority
        </p>

    </div>


    <!-- ========================================================
         EDITORIAL LEADERSHIP
    ========================================================= -->

    <section class="mb-5">

        <div class="mb-4">

            <h3
                class="fw-bold mb-2"
                style="
                    color:#173f4f;
                    font-size:24px;
                "
            >
                Editorial Leadership
            </h3>

            <p
                class="mb-0"
                style="
                    color:#71818b;
                    font-size:15px;
                "
            >
                Senior editorial leadership of the journal
            </p>

        </div>


        <div class="row g-4">

            <!-- Chairman -->

            <div class="col-lg-6">

                <div
                    class="h-100 p-4 rounded-4"
                    style="
                        background:#ffffff;
                        border:1px solid #e3ebef;
                        box-shadow:0 8px 28px rgba(23,63,79,.07);
                    "
                >

                    <div
                        class="d-flex flex-column flex-sm-row
                               align-items-sm-start gap-3"
                    >

                        <div
                            class="d-flex align-items-center
                                   justify-content-center flex-shrink-0"
                            style="
                                width:58px;
                                height:58px;
                                border-radius:16px;
                                background:#e8f3f5;
                                color:#0b6675;
                                font-weight:800;
                                font-size:20px;
                            "
                        >
                            SA
                        </div>

                        <div class="flex-grow-1">

                            <span
                                class="d-inline-block px-3 py-1
                                       rounded-pill mb-2"
                                style="
                                    background:#0b6675;
                                    color:#ffffff;
                                    font-size:12px;
                                    font-weight:700;
                                    letter-spacing:.04em;
                                "
                            >
                                CHAIRMAN
                            </span>

                            <h4
                                class="fw-bold mb-2"
                                style="
                                    color:#173f4f;
                                    font-size:20px;
                                "
                            >
                                Professor Dr. Sayeba Akhter
                            </h4>

                            <p
                                class="mb-1"
                                style="
                                    color:#445d69;
                                    font-size:15px;
                                "
                            >
                                Chairman, Executive Committee
                            </p>

                            <p
                                class="mb-1 fw-semibold"
                                style="
                                    color:#2d5361;
                                    font-size:15px;
                                "
                            >
                                Bangladesh Medical Research Council (BMRC)
                            </p>

                            <p
                                class="mb-0"
                                style="
                                    color:#7b8991;
                                    font-size:14px;
                                "
                            >
                                BMRC Bhaban, Mohakhali, Dhaka
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Chief Editor -->

            <div class="col-lg-6">

                <div
                    class="h-100 p-4 rounded-4"
                    style="
                        background:#ffffff;
                        border:1px solid #e3ebef;
                        box-shadow:0 8px 28px rgba(23,63,79,.07);
                    "
                >

                    <div
                        class="d-flex flex-column flex-sm-row
                               align-items-sm-start gap-3"
                    >

                        <div
                            class="d-flex align-items-center
                                   justify-content-center flex-shrink-0"
                            style="
                                width:58px;
                                height:58px;
                                border-radius:16px;
                                background:#edf5f3;
                                color:#176b5b;
                                font-weight:800;
                                font-size:20px;
                            "
                        >
                            MH
                        </div>

                        <div class="flex-grow-1">

                            <span
                                class="d-inline-block px-3 py-1
                                       rounded-pill mb-2"
                                style="
                                    background:#176b5b;
                                    color:#ffffff;
                                    font-size:12px;
                                    font-weight:700;
                                    letter-spacing:.04em;
                                "
                            >
                                CHIEF EDITOR
                            </span>

                            <h4
                                class="fw-bold mb-2"
                                style="
                                    color:#173f4f;
                                    font-size:20px;
                                "
                            >
                                Professor Dr. Md. Mozammel Hoque
                            </h4>

                            <p
                                class="mb-1"
                                style="
                                    color:#445d69;
                                    font-size:15px;
                                "
                            >
                                Professor of Biochemistry &amp;
                                Molecular Biology
                            </p>

                            <p
                                class="mb-1 fw-semibold"
                                style="
                                    color:#2d5361;
                                    font-size:15px;
                                "
                            >
                                Bangladesh Medical University (BMU)
                            </p>

                            <p
                                class="mb-0"
                                style="
                                    color:#7b8991;
                                    font-size:14px;
                                "
                            >
                                Shahbag, Dhaka
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Editor -->

            <div class="col-lg-6">

                <div
                    class="h-100 p-4 rounded-4"
                    style="
                        background:#ffffff;
                        border:1px solid #e3ebef;
                        box-shadow:0 8px 28px rgba(23,63,79,.07);
                    "
                >

                    <div
                        class="d-flex flex-column flex-sm-row
                               align-items-sm-start gap-3"
                    >

                        <div
                            class="d-flex align-items-center
                                   justify-content-center flex-shrink-0"
                            style="
                                width:58px;
                                height:58px;
                                border-radius:16px;
                                background:#f2f0fb;
                                color:#5c4b8a;
                                font-weight:800;
                                font-size:20px;
                            "
                        >
                            MM
                        </div>

                        <div class="flex-grow-1">

                            <span
                                class="d-inline-block px-3 py-1
                                       rounded-pill mb-2"
                                style="
                                    background:#5c4b8a;
                                    color:#ffffff;
                                    font-size:12px;
                                    font-weight:700;
                                    letter-spacing:.04em;
                                "
                            >
                                EDITOR
                            </span>

                            <h4
                                class="fw-bold mb-2"
                                style="
                                    color:#173f4f;
                                    font-size:20px;
                                "
                            >
                                Professor (Brig. Gen. Rtd.) Mamun Mostafi
                            </h4>

                            <p
                                class="mb-1"
                                style="
                                    color:#445d69;
                                    font-size:15px;
                                "
                            >
                                Head, Department of Medicine &amp;
                                Nephrology
                            </p>

                            <p
                                class="mb-1 fw-semibold"
                                style="
                                    color:#2d5361;
                                    font-size:15px;
                                "
                            >
                                Gonoshasthaya Samajvittik Medical College
                                &amp; Gonoshasthaya Kendra
                            </p>

                            <p
                                class="mb-0"
                                style="
                                    color:#7b8991;
                                    font-size:14px;
                                "
                            >
                                Dhaka
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Executive Editor -->

            <div class="col-lg-6">

                <div
                    class="h-100 p-4 rounded-4"
                    style="
                        background:#ffffff;
                        border:1px solid #e3ebef;
                        box-shadow:0 8px 28px rgba(23,63,79,.07);
                    "
                >

                    <div
                        class="d-flex flex-column flex-sm-row
                               align-items-sm-start gap-3"
                    >

                        <div
                            class="d-flex align-items-center
                                   justify-content-center flex-shrink-0"
                            style="
                                width:58px;
                                height:58px;
                                border-radius:16px;
                                background:#fff4e6;
                                color:#9a5d16;
                                font-weight:800;
                                font-size:20px;
                            "
                        >
                            KB
                        </div>

                        <div class="flex-grow-1">

                            <span
                                class="d-inline-block px-3 py-1
                                       rounded-pill mb-2"
                                style="
                                    background:#9a5d16;
                                    color:#ffffff;
                                    font-size:12px;
                                    font-weight:700;
                                    letter-spacing:.04em;
                                "
                            >
                                EXECUTIVE EDITOR
                            </span>

                            <h4
                                class="fw-bold mb-2"
                                style="
                                    color:#173f4f;
                                    font-size:20px;
                                "
                            >
                                Dr. Kazi Saifuddin Bennoor
                            </h4>

                            <p
                                class="mb-1"
                                style="
                                    color:#445d69;
                                    font-size:15px;
                                "
                            >
                                Director
                            </p>

                            <p
                                class="mb-1 fw-semibold"
                                style="
                                    color:#2d5361;
                                    font-size:15px;
                                "
                            >
                                Bangladesh Medical Research Council (BMRC)
                            </p>

                            <p
                                class="mb-0"
                                style="
                                    color:#7b8991;
                                    font-size:14px;
                                "
                            >
                                Mohakhali, Dhaka
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- ========================================================
         EDITORIAL BOARD MEMBERS
    ========================================================= -->

    <section>

        <div class="mb-4">

            <h3
                class="fw-bold mb-2"
                style="
                    color:#173f4f;
                    font-size:24px;
                "
            >
                Editorial Board Members
            </h3>

            <p
                class="mb-0"
                style="
                    color:#71818b;
                    font-size:15px;
                "
            >
                Members representing diverse academic,
                clinical and research disciplines
            </p>

        </div>


        <div class="row g-4">

            <!-- Member 1 -->

            <div class="col-lg-6">

                <div
                    class="h-100 p-4 rounded-4"
                    style="
                        background:#fff;
                        border:1px solid #e4ebef;
                    "
                >

                    <div
                        class="d-flex align-items-start gap-3"
                    >

                        <div
                            class="d-flex align-items-center
                                   justify-content-center flex-shrink-0"
                            style="
                                width:48px;
                                height:48px;
                                border-radius:50%;
                                background:#eef5f7;
                                color:#0d6574;
                                font-weight:800;
                            "
                        >
                            MZ
                        </div>

                        <div>

                            <h5
                                class="fw-bold mb-1"
                                style="color:#173f4f;"
                            >
                                Professor Dr. M Mostafa Zaman
                            </h5>

                            <div
                                class="mb-2"
                                style="
                                    color:#0d6574;
                                    font-size:13px;
                                    font-weight:700;
                                "
                            >
                                EDITORIAL BOARD MEMBER
                            </div>

                            <p class="mb-1" style="color:#4d626d;">
                                Executive Editor, BSMMU Journal
                            </p>

                            <p class="mb-1" style="color:#4d626d;">
                                Department of Pharmacology
                            </p>

                            <p class="mb-0" style="color:#76858e;">
                                Bangladesh Medical University (BMU),
                                Shahbag, Dhaka
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Member 2 -->

            <div class="col-lg-6">

                <div
                    class="h-100 p-4 rounded-4"
                    style="
                        background:#fff;
                        border:1px solid #e4ebef;
                    "
                >

                    <div class="d-flex align-items-start gap-3">

                        <div
                            class="d-flex align-items-center
                                   justify-content-center flex-shrink-0"
                            style="
                                width:48px;
                                height:48px;
                                border-radius:50%;
                                background:#eef5f7;
                                color:#0d6574;
                                font-weight:800;
                            "
                        >
                            AR
                        </div>

                        <div>

                            <h5
                                class="fw-bold mb-1"
                                style="color:#173f4f;"
                            >
                                Dr. Ahmed Ehsanur Rahman
                            </h5>

                            <div
                                class="mb-2"
                                style="
                                    color:#0d6574;
                                    font-size:13px;
                                    font-weight:700;
                                "
                            >
                                EDITORIAL BOARD MEMBER
                            </div>

                            <p class="mb-1" style="color:#4d626d;">
                                Scientist, Maternal and Child Health Division
                            </p>

                            <p class="mb-0" style="color:#76858e;">
                                ICDDRB, Mohakhali, Dhaka
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Member 3 -->

            <div class="col-lg-6">

                <div
                    class="h-100 p-4 rounded-4"
                    style="background:#fff;border:1px solid #e4ebef;"
                >

                    <div class="d-flex align-items-start gap-3">

                        <div
                            class="d-flex align-items-center
                                   justify-content-center flex-shrink-0"
                            style="
                                width:48px;
                                height:48px;
                                border-radius:50%;
                                background:#eef5f7;
                                color:#0d6574;
                                font-weight:800;
                            "
                        >
                            FC
                        </div>

                        <div>

                            <h5
                                class="fw-bold mb-1"
                                style="color:#173f4f;"
                            >
                                Dr. Fazle Rabbi Chowdhury
                            </h5>

                            <div
                                class="mb-2"
                                style="
                                    color:#0d6574;
                                    font-size:13px;
                                    font-weight:700;
                                "
                            >
                                EDITORIAL BOARD MEMBER
                            </div>

                            <p class="mb-1" style="color:#4d626d;">
                                Associate Professor,
                                Department of Internal Medicine
                            </p>

                            <p class="mb-0" style="color:#76858e;">
                                Bangladesh Medical University (BMU),
                                Shahbag, Dhaka
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Member 4 -->

            <div class="col-lg-6">

                <div
                    class="h-100 p-4 rounded-4"
                    style="background:#fff;border:1px solid #e4ebef;"
                >

                    <div class="d-flex align-items-start gap-3">

                        <div
                            class="d-flex align-items-center
                                   justify-content-center flex-shrink-0"
                            style="
                                width:48px;
                                height:48px;
                                border-radius:50%;
                                background:#eef5f7;
                                color:#0d6574;
                                font-weight:800;
                            "
                        >
                            NH
                        </div>

                        <div>

                            <h5
                                class="fw-bold mb-1"
                                style="color:#173f4f;"
                            >
                                Dr. Md. Nazmul Hasan
                            </h5>

                            <div
                                class="mb-2"
                                style="
                                    color:#0d6574;
                                    font-size:13px;
                                    font-weight:700;
                                "
                            >
                                EDITORIAL BOARD MEMBER
                            </div>

                            <p class="mb-1" style="color:#4d626d;">
                                Associate Professor,
                                Department of Internal Medicine
                            </p>

                            <p class="mb-0" style="color:#76858e;">
                                Bangladesh Medical University (BMU),
                                Shahbag, Dhaka
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Member 5 -->

            <div class="col-lg-6">

                <div
                    class="h-100 p-4 rounded-4"
                    style="background:#fff;border:1px solid #e4ebef;"
                >

                    <div class="d-flex align-items-start gap-3">

                        <div
                            class="d-flex align-items-center
                                   justify-content-center flex-shrink-0"
                            style="
                                width:48px;
                                height:48px;
                                border-radius:50%;
                                background:#eef5f7;
                                color:#0d6574;
                                font-weight:800;
                            "
                        >
                            MI
                        </div>

                        <div>

                            <h5
                                class="fw-bold mb-1"
                                style="color:#173f4f;"
                            >
                                Dr. A K M Monwarul Islam
                            </h5>

                            <div
                                class="mb-2"
                                style="
                                    color:#0d6574;
                                    font-size:13px;
                                    font-weight:700;
                                "
                            >
                                EDITORIAL BOARD MEMBER
                            </div>

                            <p class="mb-1" style="color:#4d626d;">
                                Associate Professor,
                                Department of Cardiology
                            </p>

                            <p class="mb-0" style="color:#76858e;">
                                National Institute of Cardiovascular
                                Diseases (NICVD), Sher-e-Bangla Nagar,
                                Dhaka
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Member 6 -->

            <div class="col-lg-6">

                <div
                    class="h-100 p-4 rounded-4"
                    style="background:#fff;border:1px solid #e4ebef;"
                >

                    <div class="d-flex align-items-start gap-3">

                        <div
                            class="d-flex align-items-center
                                   justify-content-center flex-shrink-0"
                            style="
                                width:48px;
                                height:48px;
                                border-radius:50%;
                                background:#eef5f7;
                                color:#0d6574;
                                font-weight:800;
                            "
                        >
                            AR
                        </div>

                        <div>

                            <h5
                                class="fw-bold mb-1"
                                style="color:#173f4f;"
                            >
                                Professor Dr. Aminur Rahman
                            </h5>

                            <div
                                class="mb-2"
                                style="
                                    color:#0d6574;
                                    font-size:13px;
                                    font-weight:700;
                                "
                            >
                                EDITORIAL BOARD MEMBER
                            </div>

                            <p class="mb-1" style="color:#4d626d;">
                                Head, Department of Neurology
                            </p>

                            <p class="mb-0" style="color:#76858e;">
                                Sir Salimullah Medical College &amp;
                                Mitford Hospital, Dhaka
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Member 7 -->

            <div class="col-lg-6">

                <div
                    class="h-100 p-4 rounded-4"
                    style="background:#fff;border:1px solid #e4ebef;"
                >

                    <div class="d-flex align-items-start gap-3">

                        <div
                            class="d-flex align-items-center
                                   justify-content-center flex-shrink-0"
                            style="
                                width:48px;
                                height:48px;
                                border-radius:50%;
                                background:#eef5f7;
                                color:#0d6574;
                                font-weight:800;
                            "
                        >
                            FR
                        </div>

                        <div>

                            <h5
                                class="fw-bold mb-1"
                                style="color:#173f4f;"
                            >
                                Professor Dr. AKM Fazlur Rahman
                            </h5>

                            <div
                                class="mb-2"
                                style="
                                    color:#0d6574;
                                    font-size:13px;
                                    font-weight:700;
                                "
                            >
                                EDITORIAL BOARD MEMBER
                            </div>

                            <p class="mb-1" style="color:#4d626d;">
                                Executive Director
                            </p>

                            <p class="mb-0" style="color:#76858e;">
                                Centre for Injury Prevention and Research,
                                Bangladesh (CIPRB), New DOHS,
                                Mohakhali, Dhaka
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Member 8 -->

            <div class="col-lg-6">

                <div
                    class="h-100 p-4 rounded-4"
                    style="background:#fff;border:1px solid #e4ebef;"
                >

                    <div class="d-flex align-items-start gap-3">

                        <div
                            class="d-flex align-items-center
                                   justify-content-center flex-shrink-0"
                            style="
                                width:48px;
                                height:48px;
                                border-radius:50%;
                                background:#eef5f7;
                                color:#0d6574;
                                font-weight:800;
                            "
                        >
                            AH
                        </div>

                        <div>

                            <h5
                                class="fw-bold mb-1"
                                style="color:#173f4f;"
                            >
                                Prof. Dr. Md. Amir Hossain
                            </h5>

                            <div
                                class="mb-2"
                                style="
                                    color:#0d6574;
                                    font-size:13px;
                                    font-weight:700;
                                "
                            >
                                EDITORIAL BOARD MEMBER
                            </div>

                            <p class="mb-1" style="color:#4d626d;">
                                Department of Medicine
                            </p>

                            <p class="mb-0" style="color:#76858e;">
                                Chittagong International Medical College
                                &amp; Hospital, Chittagong
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Member 9 -->

            <div class="col-lg-6">

                <div
                    class="h-100 p-4 rounded-4"
                    style="background:#fff;border:1px solid #e4ebef;"
                >

                    <div class="d-flex align-items-start gap-3">

                        <div
                            class="d-flex align-items-center
                                   justify-content-center flex-shrink-0"
                            style="
                                width:48px;
                                height:48px;
                                border-radius:50%;
                                background:#eef5f7;
                                color:#0d6574;
                                font-weight:800;
                            "
                        >
                            AM
                        </div>

                        <div>

                            <h5
                                class="fw-bold mb-1"
                                style="color:#173f4f;"
                            >
                                Professor Dr. Abu Kholdun Al-Mahmood
                            </h5>

                            <div
                                class="mb-2"
                                style="
                                    color:#0d6574;
                                    font-size:13px;
                                    font-weight:700;
                                "
                            >
                                EDITORIAL BOARD MEMBER
                            </div>

                            <p class="mb-1" style="color:#4d626d;">
                                Vice Principal; Professor &amp; Head,
                                Department of Biochemistry
                            </p>

                            <p class="mb-0" style="color:#76858e;">
                                Ibn Sina Medical College Hospital,
                                Kallyanpur, Dhaka
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Member 10 -->

            <div class="col-lg-6">

                <div
                    class="h-100 p-4 rounded-4"
                    style="background:#fff;border:1px solid #e4ebef;"
                >

                    <div class="d-flex align-items-start gap-3">

                        <div
                            class="d-flex align-items-center
                                   justify-content-center flex-shrink-0"
                            style="
                                width:48px;
                                height:48px;
                                border-radius:50%;
                                background:#eef5f7;
                                color:#0d6574;
                                font-weight:800;
                            "
                        >
                            TI
                        </div>

                        <div>

                            <h5
                                class="fw-bold mb-1"
                                style="color:#173f4f;"
                            >
                                Dr. Md. Taizul Islam
                            </h5>

                            <div
                                class="mb-2"
                                style="
                                    color:#0d6574;
                                    font-size:13px;
                                    font-weight:700;
                                "
                            >
                                EDITORIAL BOARD MEMBER
                            </div>

                            <p class="mb-1" style="color:#4d626d;">
                                Professor (Adjunct)
                            </p>

                            <p class="mb-0" style="color:#76858e;">
                                North South University, Dhaka
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Member 11 -->

            <div class="col-lg-6">

                <div
                    class="h-100 p-4 rounded-4"
                    style="background:#fff;border:1px solid #e4ebef;"
                >

                    <div class="d-flex align-items-start gap-3">

                        <div
                            class="d-flex align-items-center
                                   justify-content-center flex-shrink-0"
                            style="
                                width:48px;
                                height:48px;
                                border-radius:50%;
                                background:#eef5f7;
                                color:#0d6574;
                                font-weight:800;
                            "
                        >
                            AR
                        </div>

                        <div>

                            <h5
                                class="fw-bold mb-1"
                                style="color:#173f4f;"
                            >
                                Professor Dr. Md. Abdur Rahim, PhD
                            </h5>

                            <div
                                class="mb-2"
                                style="
                                    color:#0d6574;
                                    font-size:13px;
                                    font-weight:700;
                                "
                            >
                                EDITORIAL BOARD MEMBER
                            </div>

                            <p class="mb-1" style="color:#4d626d;">
                                Head, Department of Community Medicine
                            </p>

                            <p class="mb-0" style="color:#76858e;">
                                South Apollo Medical College, Barisal
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Member 12 -->

            <div class="col-lg-6">

                <div
                    class="h-100 p-4 rounded-4"
                    style="background:#fff;border:1px solid #e4ebef;"
                >

                    <div class="d-flex align-items-start gap-3">

                        <div
                            class="d-flex align-items-center
                                   justify-content-center flex-shrink-0"
                            style="
                                width:48px;
                                height:48px;
                                border-radius:50%;
                                background:#eef5f7;
                                color:#0d6574;
                                font-weight:800;
                            "
                        >
                            RB
                        </div>

                        <div>

                            <h5
                                class="fw-bold mb-1"
                                style="color:#173f4f;"
                            >
                                Professor Dr. Mosammad Rashida Begum
                            </h5>

                            <div
                                class="mb-2"
                                style="
                                    color:#0d6574;
                                    font-size:13px;
                                    font-weight:700;
                                "
                            >
                                EDITORIAL BOARD MEMBER
                            </div>

                            <p class="mb-1" style="color:#4d626d;">
                                Chief Consultant,
                                Gynecology &amp; Obstetrics
                            </p>

                            <p class="mb-0" style="color:#76858e;">
                                Infertility Care &amp; Research Center
                                (ICRC), Mohammadpur, Dhaka
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Member 13 -->

            <div class="col-lg-6">

                <div
                    class="h-100 p-4 rounded-4"
                    style="background:#fff;border:1px solid #e4ebef;"
                >

                    <div class="d-flex align-items-start gap-3">

                        <div
                            class="d-flex align-items-center
                                   justify-content-center flex-shrink-0"
                            style="
                                width:48px;
                                height:48px;
                                border-radius:50%;
                                background:#eef5f7;
                                color:#0d6574;
                                font-weight:800;
                            "
                        >
                            AS
                        </div>

                        <div>

                            <h5
                                class="fw-bold mb-1"
                                style="color:#173f4f;"
                            >
                                Dr. S. M. Anwar Sadat
                            </h5>

                            <div
                                class="mb-2"
                                style="
                                    color:#0d6574;
                                    font-size:13px;
                                    font-weight:700;
                                "
                            >
                                EDITORIAL BOARD MEMBER
                            </div>

                            <p class="mb-1" style="color:#4d626d;">
                                BDS, MCPS, FCPS, MS,
                                MPH, FDSRCPS (Glasgow),
                                FDS Res (England)
                            </p>

                            <p class="mb-1" style="color:#4d626d;">
                                Associate Professor,
                                Department of Oral &amp;
                                Maxillofacial Surgery
                            </p>

                            <p class="mb-0" style="color:#76858e;">
                                Dhaka Dental College; Secretary General,
                                Bangladesh Association of Oral &amp;
                                Maxillofacial Surgery (BAMOS)
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Member 14 -->

            <div class="col-lg-6">

                <div
                    class="h-100 p-4 rounded-4"
                    style="background:#fff;border:1px solid #e4ebef;"
                >

                    <div class="d-flex align-items-start gap-3">

                        <div
                            class="d-flex align-items-center
                                   justify-content-center flex-shrink-0"
                            style="
                                width:48px;
                                height:48px;
                                border-radius:50%;
                                background:#eef5f7;
                                color:#0d6574;
                                font-weight:800;
                            "
                        >
                            TA
                        </div>

                        <div>

                            <h5
                                class="fw-bold mb-1"
                                style="color:#173f4f;"
                            >
                                Dr. Tanveer Ahmed
                            </h5>

                            <div
                                class="mb-2"
                                style="
                                    color:#0d6574;
                                    font-size:13px;
                                    font-weight:700;
                                "
                            >
                                EDITORIAL BOARD MEMBER
                            </div>

                            <p class="mb-1" style="color:#4d626d;">
                                MBBS, FCPS (Plastic Surgery),
                                FACS; Honorary, CMEd
                            </p>

                            <p class="mb-1" style="color:#4d626d;">
                                Associate Professor
                            </p>

                            <p class="mb-0" style="color:#76858e;">
                                National Institute of Burn and
                                Plastic Surgery, Dhaka
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Member 15 -->

            <div class="col-lg-6">

                <div
                    class="h-100 p-4 rounded-4"
                    style="background:#fff;border:1px solid #e4ebef;"
                >

                    <div class="d-flex align-items-start gap-3">

                        <div
                            class="d-flex align-items-center
                                   justify-content-center flex-shrink-0"
                            style="
                                width:48px;
                                height:48px;
                                border-radius:50%;
                                background:#eef5f7;
                                color:#0d6574;
                                font-weight:800;
                            "
                        >
                            AH
                        </div>

                        <div>

                            <h5
                                class="fw-bold mb-1"
                                style="color:#173f4f;"
                            >
                                Brigadier General Md. Ahsan Habib (Retd)
                            </h5>

                            <div
                                class="mb-2"
                                style="
                                    color:#0d6574;
                                    font-size:13px;
                                    font-weight:700;
                                "
                            >
                                EDITORIAL BOARD MEMBER
                            </div>

                            <p class="mb-1" style="color:#4d626d;">
                                Professor of Anatomy and Director,
                                Medical Education Unit
                            </p>

                            <p class="mb-0" style="color:#76858e;">
                                International Medical College
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- ========================================================
         FOOTNOTE
    ========================================================= -->

    <div
        class="mt-5 p-4 rounded-4"
        style="
            background:#f6f9fa;
            border:1px solid #e3ebef;
        "
    >

        <div class="d-flex align-items-start gap-3">

            <div
                class="d-flex align-items-center
                       justify-content-center flex-shrink-0"
                style="
                    width:42px;
                    height:42px;
                    border-radius:50%;
                    background:#e7f2f4;
                    color:#0b6574;
                    font-weight:700;
                "
            >
                i
            </div>

            <div>

                <h6
                    class="fw-bold mb-1"
                    style="color:#173f4f;"
                >
                    Editorial Board Information
                </h6>

                <p
                    class="mb-0"
                    style="
                        color:#667983;
                        font-size:14px;
                        line-height:1.7;
                    "
                >
                    Affiliations and professional positions are presented
                    according to the information provided in the journal\'s
                    editorial board record.
                </p>

            </div>

        </div>

    </div>

</div>',
  'content_mode' => 'html',
  'short_description' => '2025-2026',
  'featured_image' => NULL,
  'show_in_menu' => 1,
  'show_on_homepage' => 0,
  'sort_order' => 2,
  'status' => 'published',
  'created_by' => null,
  'updated_by' => null,
  'created_at' => '2026-09-12 06:51:10',
  'updated_at' => '2026-09-12 06:58:42',
),
            array (
  'id' => 2,
  'title' => 'BMRC Journal',
  'slug' => 'bmrc-journal',
  'menu_group' => 'about',
  'content' => '<style>
    
    .journal-about {
        --primary: #00695c;
        --primary-dark: #004d40;
        --primary-light: #e8f4f2;
        --secondary: #b68b2c;
        --text-dark: #253238;
        --text-muted: #66747a;
        --border: #e2e9e8;
        --background: #f6f8f8;
        --white: #ffffff;

        background: var(--background);
        padding: 38px 0 60px;
        color: var(--text-dark);
        font-family: Arial, Helvetica, sans-serif;
    }

    .journal-about .about-container {
        max-width: 1180px;
        width: calc(100% - 30px);
        margin: 0 auto;
    }

      .journal-about .journal-breadcrumb {
        margin-bottom: 18px;
        font-size: 13px;
        color: #74817f;
    }

    .journal-about .journal-breadcrumb a {
        color: var(--primary);
        text-decoration: none;
    }

    .journal-about .journal-breadcrumb a:hover {
        text-decoration: underline;
    }

    .journal-about .journal-breadcrumb span {
        margin: 0 8px;
        color: #a6afad;
    }

    .journal-about .about-hero {
        position: relative;
        overflow: hidden;
        background:
            linear-gradient(
                120deg,
                rgba(0, 77, 64, 0.98),
                rgba(0, 105, 92, 0.94)
            );
        border-radius: 12px;
        padding: 42px 45px;
        color: #ffffff;
        box-shadow: 0 8px 26px rgba(0, 77, 64, 0.12);
        margin-bottom: 25px;
    }

    .journal-about .about-hero::after {
        content: "";
        position: absolute;
        width: 240px;
        height: 240px;
        border: 45px solid rgba(255,255,255,.06);
        border-radius: 50%;
        right: -70px;
        top: -90px;
    }

    .journal-about .hero-label {
        display: inline-block;
        padding: 5px 11px;
        margin-bottom: 12px;
        border: 1px solid rgba(255,255,255,.35);
        border-radius: 30px;
        font-size: 11px;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-weight: 700;
    }

    .journal-about .about-hero h1 {
        position: relative;
        z-index: 2;
        margin: 0 0 10px;
        font-family: Georgia, "Times New Roman", serif;
        font-size: 36px;
        font-weight: 700;
        line-height: 1.25;
    }

    .journal-about .about-hero p {
        position: relative;
        z-index: 2;
        margin: 0;
        max-width: 760px;
        color: rgba(255,255,255,.88);
        font-size: 15px;
        line-height: 1.8;
    }

  
    .journal-about .journal-facts {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 25px;
    }

    .journal-about .fact-card {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 9px;
        padding: 19px 20px;
        min-height: 108px;
        box-shadow: 0 2px 9px rgba(0,0,0,.035);
    }

    .journal-about .fact-label {
        display: block;
        margin-bottom: 6px;
        color: #7a8885;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .7px;
    }

    .journal-about .fact-value {
        color: var(--primary-dark);
        font-family: Georgia, "Times New Roman", serif;
        font-size: 17px;
        font-weight: 700;
        line-height: 1.4;
    }

    .journal-about .fact-small {
        display: block;
        margin-top: 4px;
        color: #778481;
        font-family: Arial, sans-serif;
        font-size: 12px;
        font-weight: 400;
    }

      .journal-about .about-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 285px;
        gap: 25px;
        align-items: start;
    }

       .journal-about .content-card {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 30px 32px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,.03);
    }

    .journal-about .content-title {
        position: relative;
        margin: 0 0 20px;
        padding-bottom: 12px;
        color: #203a36;
        font-family: Georgia, "Times New Roman", serif;
        font-size: 23px;
        font-weight: 700;
    }

    .journal-about .content-title::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        width: 46px;
        height: 3px;
        background: var(--secondary);
        border-radius: 3px;
    }

    .journal-about .content-card p {
        margin: 0 0 16px;
        color: #4f5d5b;
        font-size: 14.5px;
        line-height: 1.85;
        text-align: justify;
    }

    .journal-about .content-card p:last-child {
        margin-bottom: 0;
    }

   
    .journal-about .scope-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px 20px;
        padding: 0;
        margin: 18px 0 0;
        list-style: none;
    }

    .journal-about .scope-list li {
        position: relative;
        padding: 10px 10px 10px 28px;
        border-bottom: 1px dashed #e3e9e8;
        color: #4d5b59;
        font-size: 14px;
    }

    .journal-about .scope-list li::before {
        content: "✓";
        position: absolute;
        left: 4px;
        top: 9px;
        color: var(--primary);
        font-weight: 700;
    }

    
    .journal-about .review-box {
        display: flex;
        gap: 17px;
        padding: 18px 20px;
        margin-top: 15px;
        background: var(--primary-light);
        border-left: 4px solid var(--primary);
        border-radius: 4px;
    }

    .journal-about .review-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: var(--primary);
        color: #fff;
        font-size: 18px;
        font-weight: bold;
    }

    .journal-about .review-box h4 {
        margin: 0 0 4px;
        color: var(--primary-dark);
        font-size: 15px;
    }

    .journal-about .review-box p {
        margin: 0;
        text-align: left;
        font-size: 13.5px;
    }

     .journal-about .sidebar-card {
        overflow: hidden;
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 9px;
        margin-bottom: 20px;
        box-shadow: 0 2px 9px rgba(0,0,0,.03);
    }

    .journal-about .sidebar-title {
        margin: 0;
        padding: 14px 17px;
        background: var(--primary-dark);
        color: #ffffff;
        font-size: 14px;
        font-weight: 700;
    }

    .journal-about .sidebar-body {
        padding: 8px 0;
    }

    .journal-about .sidebar-link {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 11px 16px;
        border-bottom: 1px solid #edf1f0;
        color: #40504d;
        font-size: 13px;
        text-decoration: none;
        transition: .2s ease;
    }

    .journal-about .sidebar-link:last-child {
        border-bottom: 0;
    }

    .journal-about .sidebar-link:hover {
        padding-left: 20px;
        color: var(--primary);
        background: #f6fbfa;
    }

    .journal-about .sidebar-link::after {
        content: "›";
        color: #92a09d;
        font-size: 18px;
    }

    .journal-about .publisher-box {
        padding: 18px;
    }

    .journal-about .publisher-name {
        margin-bottom: 7px;
        color: var(--primary-dark);
        font-family: Georgia, "Times New Roman", serif;
        font-size: 16px;
        font-weight: 700;
    }

    .journal-about .publisher-box p {
        margin: 0 0 6px;
        color: #667370;
        font-size: 12.5px;
        line-height: 1.65;
    }

    @media (max-width: 992px) {
        .journal-about .journal-facts {
            grid-template-columns: repeat(2, 1fr);
        }

        .journal-about .about-layout {
            grid-template-columns: 1fr;
        }

        .journal-about .about-sidebar {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .journal-about .sidebar-card {
            margin-bottom: 0;
        }
    }

    @media (max-width: 650px) {
        .journal-about {
            padding-top: 20px;
        }

        .journal-about .about-container {
            width: calc(100% - 20px);
        }

        .journal-about .about-hero {
            padding: 29px 22px;
            border-radius: 8px;
        }

        .journal-about .about-hero h1 {
            font-size: 28px;
        }

        .journal-about .journal-facts {
            grid-template-columns: 1fr;
        }

        .journal-about .scope-list {
            grid-template-columns: 1fr;
        }

        .journal-about .content-card {
            padding: 23px 20px;
        }

        .journal-about .about-sidebar {
            grid-template-columns: 1fr;
        }
    }
</style>


<section class="journal-about">

    <div class="about-container">

           <div class="journal-breadcrumb">

            <a href="{{ url(\'/\') }}">
                Home
            </a>

            <span>/</span>

            <strong>About the Journal</strong>

        </div>


        <div class="about-hero">

            <span class="hero-label">
                About the Journal
            </span>

            <h1>
                Bangladesh Medical Research Council Bulletin
            </h1>

            <p>
                The official scientific publication of the Bangladesh
                Medical Research Council, dedicated to the dissemination
                of high-quality research and scientific knowledge in
                medicine, public health and the health sciences.
            </p>

        </div>


            <div class="journal-facts">

            <div class="fact-card">
                <span class="fact-label">Publisher</span>

                <div class="fact-value">
                    Bangladesh Medical Research Council

                    <span class="fact-small">
                        BMRC
                    </span>
                </div>
            </div>


            <div class="fact-card">
                <span class="fact-label">Established</span>

                <div class="fact-value">
                    1975

                    <span class="fact-small">
                        Publishing since April 1975
                    </span>
                </div>
            </div>


            <div class="fact-card">
                <span class="fact-label">Publication Frequency</span>

                <div class="fact-value">
                    Triannual

                    <span class="fact-small">
                        April · August · December
                    </span>
                </div>
            </div>


            <div class="fact-card">
                <span class="fact-label">Review Process</span>

                <div class="fact-value">
                    Peer Reviewed

                    <span class="fact-small">
                        Scientific editorial assessment
                    </span>
                </div>
            </div>

        </div>


        <div class="about-layout">

            <div class="about-main">


                <article class="content-card">

                    <h2 class="content-title">
                        About the Journal
                    </h2>

                    <p>
                        The <strong>Bangladesh Medical Research Council
                        Bulletin</strong> is the official scientific
                        publication of the
                        <strong>Bangladesh Medical Research Council
                        (BMRC)</strong>.
                        The Bulletin has been published since April 1975
                        as part of the Council\'s commitment to promoting
                        and disseminating health and medical research.
                    </p>

                    <p>
                        The journal provides a scholarly platform for
                        researchers, clinicians, public health
                        professionals, academicians and other health
                        science professionals to communicate research
                        findings that contribute to the advancement of
                        medical knowledge, evidence-based health care,
                        public health practice and health policy.
                    </p>

                    <p>
                        Through scientific publication and knowledge
                        dissemination, the journal supports BMRC\'s
                        broader mandate of promoting, coordinating and
                        strengthening health research in Bangladesh while
                        facilitating the exchange of scientific
                        information with the national and international
                        research community.
                    </p>

                </article>

                <article class="content-card">

                    <h2 class="content-title">
                        Aims and Scope
                    </h2>

                    <p>
                        The Bangladesh Medical Research Council Bulletin
                        aims to advance knowledge in medicine and health
                        sciences by publishing scientifically sound,
                        relevant and ethically conducted research.
                        The journal welcomes scholarly work addressing
                        important health challenges and developments in
                        clinical medicine, biomedical science, public
                        health and related disciplines.
                    </p>

                    <ul class="scope-list">

                        <li>
                            Clinical Medicine
                        </li>

                        <li>
                            Public Health
                        </li>

                        <li>
                            Epidemiology
                        </li>

                        <li>
                            Biomedical Sciences
                        </li>

                        <li>
                            Community Medicine
                        </li>

                        <li>
                            Reproductive Health
                        </li>

                        <li>
                            Maternal and Child Health
                        </li>

                        <li>
                            Nutrition and Health
                        </li>

                        <li>
                            Health Systems Research
                        </li>

                        <li>
                            Medical Education
                        </li>

                        <li>
                            Disease Prevention and Control
                        </li>

                        <li>
                            Health Policy and Research
                        </li>

                    </ul>

                </article>


       
                <article class="content-card">

                    <h2 class="content-title">
                        Peer Review and Editorial Quality
                    </h2>

                    <p>
                        Manuscripts submitted to the journal are
                        evaluated for their scientific relevance,
                        originality, methodological quality and
                        suitability for publication. Papers considered
                        appropriate for the journal are subjected to
                        peer review before an editorial decision is made.
                    </p>

                    <div class="review-box">

                        <div class="review-icon">
                            ✓
                        </div>

                        <div>
                            <h4>
                                Scientific Peer Review
                            </h4>

                            <p>
                                The journal uses independent expert
                                assessment to support the quality,
                                integrity and scientific validity of
                                published research.
                            </p>
                        </div>

                    </div>

                </article>


                <article class="content-card">

                    <h2 class="content-title">
                        Publication Frequency
                    </h2>

                    <p>
                        The Bangladesh Medical Research Council Bulletin
                        is published <strong>three times a year</strong>.
                        Regular issues are published in
                        <strong>April, August and December</strong>.
                    </p>

                    <p>
                        This publication schedule supports the regular
                        dissemination of scientific research findings
                        and provides researchers with an established
                        national platform for sharing evidence relevant
                        to health and medical sciences.
                    </p>

                </article>


                <article class="content-card">

                    <h2 class="content-title">
                        About the Publisher
                    </h2>

                    <p>
                        The journal is published by the
                        <strong>Bangladesh Medical Research Council
                        (BMRC)</strong>, an autonomous body under the
                        Ministry of Health and Family Welfare,
                        Government of the People\'s Republic of
                        Bangladesh.
                    </p>

                    <p>
                        BMRC serves as a focal organization for health
                        research in Bangladesh. Its activities include
                        promoting and coordinating scientific research,
                        developing research capacity and disseminating
                        research findings for their effective
                        utilization in health care and health policy.
                    </p>

                </article>


                <article class="content-card">

                    <h2 class="content-title">
                        Editorial Commitment
                    </h2>

                    <p>
                        The Bangladesh Medical Research Council Bulletin
                        is committed to maintaining high standards of
                        scientific quality, research integrity,
                        transparency and responsible scholarly
                        communication.
                    </p>

                    <p>
                        Authors, reviewers and editors are expected to
                        observe internationally accepted principles of
                        research ethics, publication ethics,
                        confidentiality, disclosure of conflicts of
                        interest and responsible reporting of research.
                    </p>

                </article>

            </div>


            <aside class="about-sidebar">


                
                <div class="sidebar-card">

                    <h3 class="sidebar-title">
                        Publisher
                    </h3>

                    <div class="publisher-box">

                        <div class="publisher-name">
                            Bangladesh Medical Research Council
                        </div>

                        <p>
                            BMRC Bhaban
                        </p>

                        <p>
                            Mohakhali, Dhaka-1212
                        </p>

                        <p>
                            Bangladesh
                        </p>

                    </div>

                </div>



                <div class="sidebar-card">

                    <h3 class="sidebar-title">
                        Publication Details
                    </h3>

                    <div class="publisher-box">

                        <p>
                            <strong>Journal:</strong><br>
                            Bangladesh Medical Research Council Bulletin
                        </p>

                        <p>
                            <strong>Frequency:</strong><br>
                            Three issues per year
                        </p>

                        <p>
                            <strong>Publication Months:</strong><br>
                            April, August &amp; December
                        </p>

                        <p>
                            <strong>Language:</strong><br>
                            English
                        </p>

                    </div>

                </div>

            </aside>

        </div>

    </div>

</section>',
  'content_mode' => 'html',
  'short_description' => NULL,
  'featured_image' => NULL,
  'show_in_menu' => 1,
  'show_on_homepage' => 0,
  'sort_order' => 1,
  'status' => 'published',
  'created_by' => null,
  'updated_by' => null,
  'created_at' => '2026-09-12 06:58:09',
  'updated_at' => '2026-09-12 07:06:32',
),
            array (
  'id' => 9,
  'title' => 'Special Issues',
  'slug' => 'special-issues',
  'menu_group' => 'journal',
  'content' => '<p>Special Issues Details&nbsp;</p>',
  'content_mode' => 'visual',
  'short_description' => NULL,
  'featured_image' => NULL,
  'show_in_menu' => 1,
  'show_on_homepage' => 0,
  'sort_order' => 1,
  'status' => 'published',
  'created_by' => null,
  'updated_by' => null,
  'created_at' => '2026-09-12 07:53:43',
  'updated_at' => '2026-09-12 07:53:43',
),
            array (
  'id' => 10,
  'title' => 'Supplement Issues',
  'slug' => 'supplement-issues',
  'menu_group' => 'journal',
  'content' => '<p>Supplement Issues</p>',
  'content_mode' => 'visual',
  'short_description' => NULL,
  'featured_image' => NULL,
  'show_in_menu' => 1,
  'show_on_homepage' => 0,
  'sort_order' => 2,
  'status' => 'published',
  'created_by' => null,
  'updated_by' => null,
  'created_at' => '2026-09-12 07:54:59',
  'updated_at' => '2026-09-12 07:54:59',
),
            array (
  'id' => 11,
  'title' => 'Instructions for Authors',
  'slug' => 'instructions-for-authors',
  'menu_group' => 'authors',
  'content' => '<p>Instructions for Authors</p>',
  'content_mode' => 'visual',
  'short_description' => NULL,
  'featured_image' => NULL,
  'show_in_menu' => 1,
  'show_on_homepage' => 0,
  'sort_order' => 2,
  'status' => 'published',
  'created_by' => null,
  'updated_by' => null,
  'created_at' => '2026-09-12 07:55:51',
  'updated_at' => '2026-09-12 08:01:49',
),
            array (
  'id' => 12,
  'title' => 'Manuscript Preparation',
  'slug' => 'manuscript-preparation',
  'menu_group' => 'authors',
  'content' => '<p>Manuscript Preparation</p>',
  'content_mode' => 'visual',
  'short_description' => NULL,
  'featured_image' => NULL,
  'show_in_menu' => 1,
  'show_on_homepage' => 0,
  'sort_order' => 3,
  'status' => 'published',
  'created_by' => null,
  'updated_by' => null,
  'created_at' => '2026-09-12 07:56:31',
  'updated_at' => '2026-09-12 08:03:36',
),
            array (
  'id' => 13,
  'title' => 'Article Types',
  'slug' => 'article-types',
  'menu_group' => 'authors',
  'content' => '<p>Article Types</p>',
  'content_mode' => 'visual',
  'short_description' => NULL,
  'featured_image' => NULL,
  'show_in_menu' => 1,
  'show_on_homepage' => 0,
  'sort_order' => 5,
  'status' => 'published',
  'created_by' => null,
  'updated_by' => null,
  'created_at' => '2026-09-12 07:57:56',
  'updated_at' => '2026-09-12 08:03:07',
),
            array (
  'id' => 14,
  'title' => 'Submission Checklist',
  'slug' => 'submission-checklist',
  'menu_group' => 'authors',
  'content' => '<p>Submission Checklist</p>',
  'content_mode' => 'visual',
  'short_description' => NULL,
  'featured_image' => NULL,
  'show_in_menu' => 1,
  'show_on_homepage' => 0,
  'sort_order' => 7,
  'status' => 'published',
  'created_by' => null,
  'updated_by' => null,
  'created_at' => '2026-09-12 07:58:34',
  'updated_at' => '2026-09-12 08:02:28',
),
            array (
  'id' => 15,
  'title' => 'Submission Fee',
  'slug' => 'submission-fee',
  'menu_group' => 'authors',
  'content' => '<p>Submission Fee</p>',
  'content_mode' => 'visual',
  'short_description' => NULL,
  'featured_image' => NULL,
  'show_in_menu' => 1,
  'show_on_homepage' => 0,
  'sort_order' => 6,
  'status' => 'published',
  'created_by' => null,
  'updated_by' => null,
  'created_at' => '2026-09-12 07:59:13',
  'updated_at' => '2026-09-12 08:02:39',
),
            array (
  'id' => 16,
  'title' => 'Publication Fee',
  'slug' => 'publication-fee',
  'menu_group' => 'authors',
  'content' => '<p>Publication Fee</p>',
  'content_mode' => 'visual',
  'short_description' => NULL,
  'featured_image' => NULL,
  'show_in_menu' => 1,
  'show_on_homepage' => 0,
  'sort_order' => 4,
  'status' => 'published',
  'created_by' => null,
  'updated_by' => null,
  'created_at' => '2026-09-12 07:59:54',
  'updated_at' => '2026-09-12 08:03:20',
),
            array (
  'id' => 17,
  'title' => 'Copyright & Licensing',
  'slug' => 'copyright-licensing',
  'menu_group' => 'authors',
  'content' => '<p>Copyright &amp; Licensing</p>',
  'content_mode' => 'visual',
  'short_description' => NULL,
  'featured_image' => NULL,
  'show_in_menu' => 1,
  'show_on_homepage' => 0,
  'sort_order' => 1,
  'status' => 'published',
  'created_by' => null,
  'updated_by' => null,
  'created_at' => '2026-09-12 08:00:23',
  'updated_at' => '2026-09-12 08:03:47',
),
            array (
  'id' => 18,
  'title' => 'Reviewer Guidelines',
  'slug' => 'reviewer-guidelines',
  'menu_group' => 'reviewers',
  'content' => '<p>Reviewer Guidelines</p>',
  'content_mode' => 'visual',
  'short_description' => NULL,
  'featured_image' => NULL,
  'show_in_menu' => 1,
  'show_on_homepage' => 0,
  'sort_order' => 1,
  'status' => 'published',
  'created_by' => null,
  'updated_by' => null,
  'created_at' => '2026-09-12 08:04:54',
  'updated_at' => '2026-09-12 08:04:54',
),
            array (
  'id' => 19,
  'title' => 'Peer Review Process',
  'slug' => 'peer-review-process',
  'menu_group' => 'reviewers',
  'content' => '<p>Peer Review Process</p>',
  'content_mode' => 'visual',
  'short_description' => NULL,
  'featured_image' => NULL,
  'show_in_menu' => 1,
  'show_on_homepage' => 0,
  'sort_order' => 2,
  'status' => 'published',
  'created_by' => null,
  'updated_by' => null,
  'created_at' => '2026-09-12 08:05:26',
  'updated_at' => '2026-09-12 08:05:26',
),
            array (
  'id' => 20,
  'title' => 'Reviewer Responsibilities',
  'slug' => 'reviewer-responsibilities',
  'menu_group' => 'reviewers',
  'content' => '<p>Reviewer Responsibilities</p>',
  'content_mode' => 'visual',
  'short_description' => NULL,
  'featured_image' => NULL,
  'show_in_menu' => 1,
  'show_on_homepage' => 0,
  'sort_order' => 3,
  'status' => 'published',
  'created_by' => null,
  'updated_by' => null,
  'created_at' => '2026-09-12 08:05:55',
  'updated_at' => '2026-09-12 08:05:55',
),
            array (
  'id' => 21,
  'title' => 'Reviewer Ethics',
  'slug' => 'reviewer-ethics',
  'menu_group' => 'reviewers',
  'content' => '<p>Reviewer Ethics</p>',
  'content_mode' => 'visual',
  'short_description' => NULL,
  'featured_image' => NULL,
  'show_in_menu' => 1,
  'show_on_homepage' => 0,
  'sort_order' => 4,
  'status' => 'published',
  'created_by' => null,
  'updated_by' => null,
  'created_at' => '2026-09-12 08:06:24',
  'updated_at' => '2026-09-12 08:06:24',
),
            array (
  'id' => 22,
  'title' => 'Conflict of Interest',
  'slug' => 'conflict-of-interest',
  'menu_group' => 'reviewers',
  'content' => '<p>Conflict of Interest</p>',
  'content_mode' => 'visual',
  'short_description' => NULL,
  'featured_image' => NULL,
  'show_in_menu' => 1,
  'show_on_homepage' => 0,
  'sort_order' => 5,
  'status' => 'published',
  'created_by' => null,
  'updated_by' => null,
  'created_at' => '2026-09-12 08:07:01',
  'updated_at' => '2026-09-12 08:07:01',
),
        ]);
    }
}
