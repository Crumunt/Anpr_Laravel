<style>
	@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

	:root {
		--clsu-green: #006300;
		--clsu-light-green: #006300;
		--clsu-dark-green: #006300;
		--clsu-accent: #f3c423;
		--clsu-hover: #068406;
	}

	body {
		font-family: 'Poppins', sans-serif;
		background-color: #f8fafc;
	}

	.clsu-bg { background-color: var(--clsu-green); }
	.clsu-text { color: var(--clsu-green); }
	.clsu-accent-bg { background-color: var(--clsu-accent); }
	.clsu-accent-text { color: var(--clsu-accent); }

	/* Cards */
	.glass-card {
		background-color: white;
		box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
		border-radius: 0.75rem;
		transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
		border: 1px solid rgba(255, 255, 255, 0.2);
	}

	.glass-card:hover {
		box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
		transform: translateY(-2px);
		border-color: rgba(0, 99, 0, 0.3);
	}

	/* Camera / dashboard helpers */
	.camera-container { height: 450px; position: relative; overflow: hidden; }
	.camera-overlay {
		position: absolute; bottom: 0; left: 0; right: 0; padding: 0.75rem;
		background: linear-gradient(to top, rgba(0,0,0,0.7), rgba(0,0,0,0)); color: white;
	}
	.plate-highlight {
		position: absolute; border: 2px solid var(--clsu-accent);
		background-color: rgba(243, 196, 35, 0.2); border-radius: 4px; animation: pulse 2s infinite;
	}
	@keyframes pulse {
		0% { box-shadow: 0 0 0 0 rgba(243, 196, 35, 0.4); }
		70% { box-shadow: 0 0 0 10px rgba(243, 196, 35, 0); }
		100% { box-shadow: 0 0 0 0 rgba(243, 196, 35, 0); }
	}

	/* Progress & spinner */
	.progress-bar { transition: width 1.5s ease-in-out; }
	.spinner {
		width: 40px; height: 40px; border: 4px solid rgba(0, 99, 0, 0.1);
		border-top: 4px solid var(--clsu-green); border-radius: 50%; animation: spin 1s linear infinite;
	}
	@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

	/* Tooltip */
	.tooltip { position: relative; display: inline-block; }
	.tooltip .tooltip-text {
		visibility: hidden; width: 120px; background-color: rgba(0,0,0,0.8); color: #fff; text-align: center;
		border-radius: 6px; padding: 5px; position: absolute; z-index: 1; bottom: 125%; left: 50%; margin-left: -60px;
		opacity: 0; transition: opacity 0.3s; font-size: 0.75rem; pointer-events: none;
	}
	.tooltip:hover .tooltip-text { visibility: visible; opacity: 1; }

	/* Accessibility */
	a:focus, button:focus { outline: 2px solid var(--clsu-accent); outline-offset: 2px; }
	.skip-link {
		position: absolute; top: -40px; left: 0; background: var(--clsu-accent); color: black; padding: 8px; z-index: 100; transition: top 0.3s;
	}
	.skip-link:focus { top: 0; }

	/* Mobile sidebar/overlay behavior */
	@media (max-width: 768px) {
		.sidebar { position: fixed; left: -100%; top: 0; height: 100%; z-index: 50; }
		.sidebar.open { left: 0; }
		.overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.5); z-index: 40; }
		.overlay.show { display: block; }
	}
</style>

