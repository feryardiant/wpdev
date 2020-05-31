describe('my awesome website', () => {
  it('should do some chai assertions', async () => {
    browser.maximizeWindow()
    await browser.url('/')

    expect(browser).toHaveUrl(process.env.WP_HOME)
  })
})
