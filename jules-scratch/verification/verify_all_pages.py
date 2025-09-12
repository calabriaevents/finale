from playwright.sync_api import sync_playwright, expect, Page

def check_mobile_menu(page: Page, url: str, screenshot_name: str):
    """Navigates to a URL, tests the mobile menu, and takes a screenshot."""
    print(f"Testing page: {url}")

    # Set a mobile viewport
    page.set_viewport_size({"width": 375, "height": 667})

    # Go to the specified page
    page.goto(url)

    # Find the mobile menu button
    mobile_menu_button = page.locator("#mobile-menu-btn")

    # Assert it's visible
    expect(mobile_menu_button).to_be_visible(timeout=10000)
    print(f"  - Menu button found on {url}")

    # Click the button to open the menu
    mobile_menu_button.click()

    # Find the mobile menu itself
    mobile_menu = page.locator("#mobile-menu")

    # Assert that the menu is now visible
    expect(mobile_menu).to_be_visible()
    print(f"  - Menu opened successfully on {url}")

    # Take a screenshot to visually confirm
    page.screenshot(path=f"jules-scratch/verification/{screenshot_name}")
    print(f"  - Screenshot taken: {screenshot_name}")

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()

    base_url = "http://localhost:8000"

    try:
        check_mobile_menu(page, f"{base_url}/index.php", "verify_index.png")
        check_mobile_menu(page, f"{base_url}/mappa.php", "verify_mappa.png")
        check_mobile_menu(page, f"{base_url}/province.php", "verify_province.png")
        check_mobile_menu(page, f"{base_url}/iscrizione-attivita.php", "verify_iscrizione.png")

    except Exception as e:
        print(f"\n\nVerification failed on one of the pages.")
        print("Final page content was:")
        print(page.content())
        print("\nError was:")
        print(e)
        raise e # Re-raise to fail the process

    finally:
        browser.close()

with sync_playwright() as playwright:
    run(playwright)
