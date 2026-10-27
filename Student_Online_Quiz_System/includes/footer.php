</main>

<footer class="tech-footer">

    <div class="container">

        <div class="row align-items-center g-4">

            <!-- BRAND / DESCRIPTION -->

            <div class="col-lg-6">

                <div class="footer-brand">

                    <h5 class="mb-3">

                        <span class="brand-icon">
                            Q
                        </span>

                        QuizCore

                    </h5>

                    <p class="footer-description mb-0">

                        Student Online Quiz System designed for
                        simple, efficient and automated online assessment.

                    </p>

                </div>

            </div>


            <!-- TECHNOLOGY / COPYRIGHT -->

            <div class="col-lg-6 text-lg-end">

                <div class="footer-tech mb-2">

                    <span>
                        HTML
                    </span>

                    <span class="footer-dot">•</span>

                    <span>
                        CSS
                    </span>

                    <span class="footer-dot">•</span>

                    <span>
                        JavaScript
                    </span>

                    <span class="footer-dot">•</span>

                    <span>
                        Bootstrap
                    </span>

                    <span class="footer-dot">•</span>

                    <span>
                        PHP
                    </span>

                    <span class="footer-dot">•</span>

                    <span>
                        MySQL
                    </span>

                </div>


                <p class="footer-copyright mb-0">

                    © <?php echo date("Y"); ?> QuizCore

                    <span class="footer-separator">
                        •
                    </span>

                    Student Online Quiz System

                </p>

            </div>

        </div>


        <div class="footer-line"></div>


        <div class="footer-bottom">

            <span>

                <i class="bi bi-shield-check me-1"></i>

                Secure Online Assessment

            </span>


            <span>

                <i class="bi bi-lightning-charge-fill me-1"></i>

                Fast & Efficient

            </span>

        </div>

    </div>

</footer>


<!-- Bootstrap -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


<!-- QuizCore JavaScript -->

<script src="../assets/js/script.js"></script>


<style>

    /* =========================================================
       QUIZCORE FOOTER
       Shared footer styling
    ========================================================= */

    .tech-footer {

        margin-top: 0;

        padding: 50px 0 25px;

        background:
            linear-gradient(
                180deg,
                var(--bg-secondary, #0b1120),
                var(--bg, #070b14)
            );

        border-top:
            1px solid
            var(--border, rgba(255,255,255,0.08));

        color:
            var(--muted, #94a3b8);

    }


    .footer-brand h5 {

        display: flex;

        align-items: center;

        gap: 8px;

        color:
            var(--text, #f8fafc);

        font-size: 1.15rem;

        font-weight: 800;

        margin: 0;

    }


    .footer-brand .brand-icon {

        width: 34px;
        height: 34px;

        display: inline-flex;

        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background:
            linear-gradient(
                135deg,
                var(--primary, #6366f1),
                var(--primary-2, #8b5cf6)
            );

        color: white;

        font-weight: 800;

        box-shadow:
            0 8px 22px
            rgba(99,102,241,0.25);

    }


    .footer-description {

        max-width: 520px;

        color:
            var(--muted, #94a3b8);

        font-size: .88rem;

        line-height: 1.7;

    }


    .footer-tech {

        color:
            var(--muted, #94a3b8);

        font-size: .78rem;

        font-weight: 600;

        line-height: 1.8;

    }


    .footer-dot {

        margin:
            0 5px;

        color:
            var(--primary, #6366f1);

    }


    .footer-copyright {

        color:
            var(--muted, #94a3b8);

        font-size: .78rem;

    }


    .footer-separator {

        margin:
            0 5px;

        color:
            var(--primary, #6366f1);

    }


    .footer-line {

        height: 1px;

        margin:
            30px 0 20px;

        background:
            var(--border, rgba(255,255,255,0.08));

    }


    .footer-bottom {

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 15px;

        color:
            var(--muted, #94a3b8);

        font-size: .75rem;

    }


    .footer-bottom i {

        color:
            var(--primary, #6366f1);

    }


    @media (max-width: 991px) {

        .footer-brand {

            text-align: center;

        }

        .footer-brand h5 {

            justify-content: center;

        }

        .footer-description {

            margin-left: auto;
            margin-right: auto;

        }

        .footer-tech {

            text-align: center;

        }

        .footer-copyright {

            text-align: center;

        }

    }


    @media (max-width: 576px) {

        .tech-footer {

            padding:
                40px 0 22px;

        }

        .footer-tech {

            font-size: .72rem;

        }

        .footer-bottom {

            flex-direction: column;

            text-align: center;

        }

    }

</style>

</body>
</html>