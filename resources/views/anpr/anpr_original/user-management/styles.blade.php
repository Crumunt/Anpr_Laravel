<style>
	/* User Management specific styles extracted from previous inline CSS */
	.user-avatar { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; color: white; font-size: 14px; }

	.status-badge { padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; }
	.status-active { background-color: #dcfce7; color: #166534; }
	.status-inactive { background-color: #fee2e2; color: #991b1b; }
	.status-pending { background-color: #fef3c7; color: #92400e; }

	.role-badge { padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 500; }
	.role-admin { background-color: #dbeafe; color: #1e40af; }
	.role-operator { background-color: #e0e7ff; color: #3730a3; }
	.role-viewer { background-color: #f3e8ff; color: #7c3aed; }

	.table-container { overflow-x: auto; border-radius: 0.75rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); }
	.table-container table { width: 100%; border-collapse: collapse; }
	.table-container th { background-color: #f8fafc; padding: 1rem; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb; }
	.table-container td { padding: 1rem; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
	.table-container tr:hover { background-color: #f9fafb; }

	.action-btn { padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; transition: all 0.2s; border: none; cursor: pointer; }
	.btn-edit { background-color: #dbeafe; color: #1e40af; }
	.btn-edit:hover { background-color: #bfdbfe; }
	.btn-delete { background-color: #fee2e2; color: #991b1b; }
	.btn-delete:hover { background-color: #fecaca; }
	.btn-view { background-color: #dcfce7; color: #166534; }
	.btn-view:hover { background-color: #bbf7d0; }

	.search-input { padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; width: 100%; max-width: 300px; transition: all 0.2s; }
	.search-input:focus { outline: none; border-color: var(--clsu-green); box-shadow: 0 0 0 3px rgba(0, 99, 0, 0.1); }
	.filter-select { padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; transition: all 0.2s; }
	.filter-select:focus { outline: none; border-color: var(--clsu-green); box-shadow: 0 0 0 3px rgba(0, 99, 0, 0.1); }

	.pagination { display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-top: 2rem; }
	.pagination-btn { padding: 0.5rem 1rem; border: 1px solid #d1d5db; background-color: white; border-radius: 0.375rem; transition: all 0.2s; }
	.pagination-btn:hover { background-color: #f9fafb; border-color: var(--clsu-green); }
	.pagination-btn.active { background-color: var(--clsu-green); color: white; border-color: var(--clsu-green); }
	.pagination-btn:disabled { opacity: 0.5; cursor: not-allowed; }

	@media (max-width: 768px) {
		.table-container { font-size: 0.875rem; }
		.table-container th, .table-container td { padding: 0.75rem 0.5rem; }
	}
</style>

