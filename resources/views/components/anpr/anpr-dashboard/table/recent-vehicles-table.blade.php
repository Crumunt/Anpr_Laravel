<div class="px-4 md:px-8 pb-4 md:pb-8">
    <x-anpr.anpr-dashboard.ui.data-table 
        title="Recent Vehicles"
        subtitle="Last 24 hours activity"
        search_placeholder="Search plates..."
        :total_entries="4"
    >
        <!-- Vehicle 1: Authorized Regular -->
        <x-anpr.anpr-dashboard.cards.vehicle-card 
            license_plate="ABC-123"
            status="authorized"
            type="regular"
            type_color="green"
            icon="car-side"
            make_model="Toyota Camry"
            color="Silver"
            time="14:32:45"
            time_ago="2 mins ago"
            location="Main Entrance"
            camera="Camera #1"
            owner_name="Michael Johnson"
            owner_role="Faculty • Engineering"
        />
        
        <!-- Vehicle 2: Pending Visitor -->
        <x-anpr.anpr-dashboard.cards.vehicle-card 
            license_plate="LMN-456"
            status="pending"
            type="visitor"
            type_color="blue"
            icon="truck-pickup"
            make_model="Ford Ranger"
            color="Blue"
            time="14:20:33"
            time_ago="14 mins ago"
            location="Main Entrance"
            camera="Camera #1"
            owner_name="John Smith"
            owner_role="Visitor • Delivery"
        />
        
        <!-- Vehicle 3: Unauthorized Unknown -->
        <x-anpr.anpr-dashboard.cards.vehicle-card 
            license_plate="PQR-987"
            status="unauthorized"
            type="unknown"
            type_color="red"
            icon="car-alt"
            make_model="Mitsubishi Montero"
            color="Black"
            time="14:15:02"
            time_ago="19 mins ago"
            location="Exit Gate"
            camera="Camera #2"
            owner_name="Unknown"
            owner_role="No Record Found"
        />
        
        <!-- Vehicle 4: Authorized Staff -->
        <x-anpr.anpr-dashboard.cards.vehicle-card 
            license_plate="XYZ-789"
            status="authorized"
            type="staff"
            type_color="purple"
            icon="shuttle-van"
            make_model="Honda CR-V"
            color="White"
            time="14:05:18"
            time_ago="29 mins ago"
            location="Exit Gate"
            camera="Camera #2"
            owner_name="Sarah Williams"
            owner_role="Staff • Admin Office"
        />
    </x-anpr.anpr-dashboard.ui.data-table>
</div> 