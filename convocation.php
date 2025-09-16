<?php
require_once __DIR__ . "/includes/header.php";
?>

<div class="container my-5">
    <h2 class="text-center mb-4 text-primary fw-bold">Bangladesh University – 3rd Convocation</h2>

    <div class="card shadow mb-4">
        <div class="container my-5">
            <h2 class="text-uppercase fw-bold mb-4" style="color:#2c2c2c;">
                MESSAGE FROM THE VICE-CHANCELLOR
            </h2>
            <hr style="width:80px; border:2px solid #b71c1c; margin-top:-10px; margin-bottom:30px;">

            <div class="row align-items-center">
                <!-- Left Column - Image -->
                <div class="col-md-4 text-center">
                    <img src="assets/images/VCsir.jpeg"
                        alt="Vice Chancellor"
                        class="img-fluid rounded shadow"
                        style="max-width: 280px;">
                </div>

                <!-- Right Column - Text -->
                <div class="col-md-8">
                    <p class="mb-3">
                        I am delighted to announce that the Bangladesh University is going to celebrate its
                        <strong>3rd Convocation</strong> in November 2025. The date is yet to finalize, based on
                        confirmation of the Hon’ble Chancellor. The graduating students from Spring 2017 to Spring
                        2025 will be eligible to participate and receive their degrees through this convocation.
                        I request our graduating students to register online as early as possible through BU Website.
                    </p>

                    <p class="mb-3">
                        I assure you that we will be celebrating this momentous occasion in a festive mood not only
                        to congratulate our talented graduates, but also to witness their transformation into skilled
                        individuals those are ready to shape lives and societies for the better future.
                    </p>

                    <p class="fw-bold mb-0">Prof. Dr. Md. Jahangir Alam</p>
                    <p class="text-muted">Vice-Chancellor</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-primary text-white fw-bold">
            Convocation Registration Form
        </div>
        <div class="card-body">
            <form action="convocation_register.php" method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Student ID</label>
                        <input type="text" name="student_id" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                </div>

                <!-- ✅ Department & Session Added -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Department</label>
                        <input type="text" name="department" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Session</label>
                        <input type="text" name="session" class="form-control" placeholder="e.g. Spring 2024" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Program</label>
                        <input type="text" name="program" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Batch</label>
                        <input type="text" name="batch" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Register Now</button>
            </form>
        </div>
    </div>
</div>

<!-- past convocation -->
<div class="container my-5">
    <div class="row">
        <div class="col-lg-12">

            <h2 style="font-weight: 300; border-bottom: 2px solid #ce5a5a; padding-bottom: 10px; margin-bottom: 20px;">3rd Convocation</h2>

            <div class="row g-3 mb-3">
                <div class="col-6 col-md-3">
                    <img src="assets/images/con1.png" class="img-fluid rounded shadow-sm" alt="Convocation event photo 1">
                </div>
                <div class="col-6 col-md-3">
                    <img src="assets/images/con2.jpg" class="img-fluid rounded shadow-sm" alt="Graduates in gowns">
                </div>
                <div class="col-6 col-md-3">
                    <img src="assets/images/con3.png" class="img-fluid rounded shadow-sm" alt="Chief guest speaking">
                </div>
                <div class="col-6 col-md-3">
                    <img src="assets/images/con4.png" class="img-fluid rounded shadow-sm" alt="Audience at convocation">
                </div>
            </div>

            <p class="text-muted" style="text-align: justify;">
                The 3rd Convocation of Bangladesh University was held on Sunday, March 5, 2023, at the Bangabandhu International Conference Center (BICC). Mr. Dipu Moni, MP, the Honorable Minister for Education, presided over the ceremony as the representative of the Honorable President of Bangladesh and Chancellor of the University. Professor Dr. Mesbah-uddin Ahmed, Chairman of the Bangladesh Accreditation Council, was the Convocation Speaker. Vice Chancellor Prof. Dr. Anwarul Haque Sharif delivered the welcome address, while the Chairman of the Board of Trustees, Mr. Jamil Azher, delivered the vote of thanks. A total of 3,000 graduates were conferred degrees at this convocation.
            </p>

        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row g-4">
        <!-- 1st Convocation -->
        <div class="col-md-6">
            <h3 class="fw-bold">1st Convocation</h3>
            <hr style="width:60px; border:2px solid #b71c1c; margin-top:-5px; margin-bottom:15px;">

            <div class="row g-2 mb-3">
                <div class="col-6">
                    <img src="assets/images/1st1.jpg" class="img-fluid rounded shadow" alt="1st Convocation 1">
                </div>
                <div class="col-6">
                    <img src="assets/images/1st2.jpg" class="img-fluid rounded shadow" alt="1st Convocation 2">
                </div>
            </div>

            <p>
                The first convocation of Bangladesh University was held on Tuesday, February 12, 2008,
                at the Bangladesh-China Friendship Conference Center. The Honorable President, Professor
                Dr. Iajuddin Ahmed, attended the convocation as the Chief Guest. The opening speech was
                delivered by the Vice-Chancellor of Bangladesh University and former Secretary,
                Quazi Azher Ali. The Treasurer of the university, Kazi Rakibuddin Ahmed, delivered the
                welcome address. A congratulatory message from Professor P. K. Abdul Aziz,
                Vice-Chancellor of Aligarh Muslim University, India, was read out by Mr. Md. Abdul Malik,
                a member of the university's Board of Trustees. The President’s Military Secretary,
                Major General Md. Aminul Karim and Secretary Md. Sirajul Islam were also present at the event.
                A total of 2,000 graduates participated in the first convocation of Bangladesh University.
            </p>
        </div>

        <!-- 2nd Convocation -->
        <div class="col-md-6">
            <h3 class="fw-bold">2nd Convocation</h3>
            <hr style="width:60px; border:2px solid #b71c1c; margin-top:-5px; margin-bottom:15px;">

            <div class="row g-2 mb-3">
                <div class="col-6">
                    <img src="assets/images/2nd1.jpg" class="img-fluid rounded shadow" alt="2nd Convocation 1">
                </div>
                <div class="col-6">
                    <img src="assets/images/2nd2.jpg" class="img-fluid rounded shadow" alt="2nd Convocation 2">
                </div>

            </div>

            <p>
                2nd Convocation of Bangladesh University held Bangladesh University (BU) held its
                2nd Convocation on 27 December, 2017 (Wednesday) at Bangabandhu International Conference Center (BICC),
                Sher-E-Bangla Nagar, Dhaka. Mr. Nurul Islam Nahid, MP, Honorable Minister for Education, as the representative
                of the Honorable President of Bangladesh and Chancellor of Bangladesh University Mr. Mohammad Abdul Hamid
                presided over the Convocation. Advocate Jahangir Kabir Nanak, MP, Dhaka-13 was present as Special Guest.
                Professor, Emeritus, Department of History, and Ex. Vice Chancellor of Chittagong University Dr. Alamgir
                Mohammad Serajuddin was the Convocation Speaker. Vice Chancellor Prof. Dr. Anwarul Haque Sharif delivered
                the welcome addresses while the Chairman, Board of Trustees, Bangladesh University delivered vote of thanks.
            </p>
        </div>
    </div>
</div>


<?php
require_once __DIR__ . "/includes/footer.php";
?>