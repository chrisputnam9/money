Vagrant::Config.run do |config|

	config.vm.box = "ubuntu/trusty64"
    # TODO export to custom base
    # - cmp install, update
    # 
	# config.vm.box_url = "http://chrisputnam.info/php7.box"

	config.vm.provision :puppet do |puppet|
        puppet.manifests_path = "local/manifests"
        puppet.manifest_file = "default.pp"
    end
	config.vm.network :hostonly, "10.0.1.2"
	config.vm.forward_port 22, 10022
	config.vm.forward_port 443, 10443
	config.vm.forward_port 80, 10080

end



Vagrant.configure("2") do |config|

	def Kernel.is_windows?
	    # Detect if we are running on Windows
	    processor, platform, *rest = RUBY_PLATFORM.split("-")
	    platform == 'mingw32'
	end

	config.vm.provider :virtualbox do |vb|
		vb.customize ["modifyvm", :id, "--memory", 512]
        vb.memory = 512
        vb.cpus = 1
        vb.customize ["modifyvm", :id, "--cpuexecutioncap", "25"]
	end

	if Kernel.is_windows?
		config.vm.synced_folder "./app", "/media/app" #, type: "smb"
		config.vm.synced_folder "./local/database", "/media/database" #, type: "smb"
	else
		config.vm.synced_folder "./app", "/media/app", type: "nfs"
		config.vm.synced_folder "./local/database", "/media/database", type: "nfs"
	end

end
